/**
 * Main JavaScript file for MDMC Music Ads Blog Theme
 *
 * Contains all custom functionality for the theme
 */

(function($) {
    'use strict';

    /**
     * Initialize all scripts
     */
    function init() {
        // Initialize sticky header
        initStickyHeader();
        
        // Initialize scroll animations
        initScrollAnimations();
        
        // Initialize mobile menu
        initMobileMenu();
        
        // Initialize lazy loading
        initLazyLoading();
    }

    /**
     * Initialize sticky header with transparency effect
     */
    function initStickyHeader() {
        const header = $('.site-header');
        const headerHeight = header.outerHeight();
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > headerHeight) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });
        
        // Trigger scroll event on page load
        $(window).trigger('scroll');
    }

    /**
     * Initialize scroll animations
     */
    function initScrollAnimations() {
        // Add fade-in class to elements that should animate
        $('.blog-card, .single-article .entry-header, .single-article .post-thumbnail, .single-article .entry-content > *, .simulator-cta').addClass('fade-in');
        
        // Function to check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.9 &&
                rect.bottom >= 0
            );
        }
        
        // Function to handle scroll animation
        function handleScrollAnimation() {
            $('.fade-in').each(function() {
                if (isInViewport(this)) {
                    $(this).addClass('visible');
                }
            });
        }
        
        // Run on scroll and on page load
        $(window).on('scroll resize', handleScrollAnimation);
        handleScrollAnimation();
    }

    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const menuToggle = $('.menu-toggle');
        const mainNavigation = $('.main-navigation');
        
        menuToggle.on('click', function() {
            const expanded = menuToggle.attr('aria-expanded') === 'true' || false;
            
            menuToggle.attr('aria-expanded', !expanded);
            mainNavigation.toggleClass('toggled');
        });
    }

    /**
     * Initialize lazy loading for images
     */
    function initLazyLoading() {
        // Check if browser supports native lazy loading
        if ('loading' in HTMLImageElement.prototype) {
            // Add loading="lazy" to all images
            const images = document.querySelectorAll('img:not([loading])');
            images.forEach(img => {
                img.setAttribute('loading', 'lazy');
            });
        } else {
            // Fallback for browsers that don't support native lazy loading
            const lazyImages = $('.blog-card img, .single-article img').not('[src]');
            
            if (lazyImages.length === 0) {
                return;
            }
            
            // Function to load images when they enter viewport
            function lazyLoad() {
                lazyImages.each(function() {
                    const img = $(this);
                    
                    if (img.is(':visible') && isInViewport(this)) {
                        img.attr('src', img.data('src'));
                        img.removeAttr('data-src');
                        img.removeClass('lazy');
                    }
                });
                
                // Remove event listeners if all images are loaded
                if (lazyImages.filter('[data-src]').length === 0) {
                    $(window).off('scroll resize', lazyLoad);
                }
            }
            
            // Function to check if element is in viewport
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 1.5 &&
                    rect.bottom >= 0
                );
            }
            
            // Run on scroll, resize, and page load
            $(window).on('scroll resize', lazyLoad);
            lazyLoad();
        }
    }

    // Initialize when document is ready
    $(document).ready(init);

})(jQuery);
