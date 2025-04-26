/**
 * Main JavaScript file for MDMC Music Ads Theme
 * 
 * This script handles all interactive functionality for the theme
 */

(function($) {
    'use strict';

    // Variables
    var $window = $(window),
        $document = $(document),
        $body = $('body'),
        $siteHeader = $('.site-header'),
        $menuToggle = $('.mobile-menu-toggle'),
        $mainNavigation = $('.main-navigation'),
        $languageToggle = $('.language-toggle'),
        $languageDropdown = $('.language-dropdown');

    // Initialize
    function init() {
        bindEvents();
        initStickyHeader();
        initLazyLoading();
        initScrollAnimations();
        initSimulator();
    }

    // Bind events
    function bindEvents() {
        $menuToggle.on('click', toggleMobileMenu);
        $window.on('scroll', handleScroll);
        $window.on('resize', handleResize);
        $document.on('click', '.category-pill', filterByCategory);
    }

    // Toggle mobile menu
    function toggleMobileMenu() {
        $menuToggle.attr('aria-expanded', function(i, attr) {
            return attr === 'true' ? 'false' : 'true';
        });
        $mainNavigation.toggleClass('toggled');
    }

    // Handle scroll events
    function handleScroll() {
        var scrollTop = $window.scrollTop();
        
        // Sticky header
        if (scrollTop > 50) {
            $siteHeader.addClass('sticky');
        } else {
            $siteHeader.removeClass('sticky');
        }
        
        // Scroll animations
        $('.animate-on-scroll').each(function() {
            var $element = $(this);
            if (isElementInViewport($element) && !$element.hasClass('animated')) {
                $element.addClass('animated');
            }
        });
    }

    // Handle resize events
    function handleResize() {
        // Close mobile menu on larger screens
        if ($window.width() > 992 && $mainNavigation.hasClass('toggled')) {
            $mainNavigation.removeClass('toggled');
            $menuToggle.attr('aria-expanded', 'false');
        }
    }

    // Initialize sticky header
    function initStickyHeader() {
        handleScroll(); // Check initial scroll position
    }

    // Initialize lazy loading for images
    function initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            // Browser supports native lazy loading
            var lazyImages = document.querySelectorAll('img[loading="lazy"]');
            lazyImages.forEach(function(img) {
                img.src = img.dataset.src;
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
            });
        } else {
            // Fallback for browsers that don't support native lazy loading
            var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
            
            if ('IntersectionObserver' in window) {
                var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            if (lazyImage.dataset.srcset) {
                                lazyImage.srcset = lazyImage.dataset.srcset;
                            }
                            lazyImage.classList.remove('lazy');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });
                
                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            }
        }
    }

    // Initialize scroll animations
    function initScrollAnimations() {
        $('.animate-on-scroll').each(function() {
            var $element = $(this);
            if (isElementInViewport($element)) {
                $element.addClass('animated');
            }
        });
    }

    // Initialize cost simulator
    function initSimulator() {
        $('#cost-simulator').on('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            var platform = $('#platform').val();
            var budget = parseInt($('#budget').val());
            var duration = parseInt($('#duration').val());
            var target = $('#target').val();
            var objective = $('#objective').val();
            
            // Calculate results
            var results = calculateResults(platform, budget, duration, target, objective);
            
            // Display results
            $('#result-reach').text(results.reach.toLocaleString());
            $('#result-engagement').text(results.engagement.toLocaleString());
            $('#result-cost').text(results.cost.toFixed(2) + '€');
            $('#result-roi').text(results.roi.toFixed(2) + '%');
            
            // Show results section
            $('.simulator-results').slideDown();
            
            // Scroll to results
            $('html, body').animate({
                scrollTop: $('.simulator-results').offset().top - 100
            }, 500);
        });
    }

    // Calculate simulator results
    function calculateResults(platform, budget, duration, target, objective) {
        // Base metrics
        var baseReach = 0;
        var baseEngagement = 0;
        var baseCost = 0;
        var baseRoi = 0;
        
        // Platform multipliers
        var platformMultipliers = {
            'spotify': { reach: 1.2, engagement: 0.8, cost: 0.9, roi: 1.1 },
            'youtube': { reach: 1.5, engagement: 1.2, cost: 1.1, roi: 1.3 },
            'facebook': { reach: 1.0, engagement: 1.0, cost: 1.0, roi: 1.0 },
            'instagram': { reach: 1.3, engagement: 1.4, cost: 1.2, roi: 1.2 },
            'tiktok': { reach: 1.8, engagement: 1.6, cost: 0.8, roi: 1.5 }
        };
        
        // Target multipliers
        var targetMultipliers = {
            'general': { reach: 1.5, engagement: 0.7, cost: 0.8, roi: 0.9 },
            'specific': { reach: 1.0, engagement: 1.0, cost: 1.0, roi: 1.0 },
            'niche': { reach: 0.6, engagement: 1.4, cost: 1.2, roi: 1.3 }
        };
        
        // Objective multipliers
        var objectiveMultipliers = {
            'awareness': { reach: 1.5, engagement: 0.7, cost: 0.8, roi: 0.9 },
            'streams': { reach: 1.2, engagement: 1.0, cost: 1.0, roi: 1.1 },
            'followers': { reach: 1.0, engagement: 1.2, cost: 1.1, roi: 1.2 },
            'engagement': { reach: 0.8, engagement: 1.5, cost: 1.2, roi: 1.3 },
            'conversion': { reach: 0.6, engagement: 1.0, cost: 1.3, roi: 1.5 }
        };
        
        // Calculate base metrics
        baseReach = budget * 100; // 100 people reached per euro
        baseEngagement = baseReach * 0.05; // 5% engagement rate
        baseCost = budget / baseEngagement; // Cost per engagement
        baseRoi = (baseEngagement * 0.1 * 5) / budget * 100; // 10% conversion, 5€ per conversion
        
        // Apply multipliers
        var reach = baseReach * platformMultipliers[platform].reach * targetMultipliers[target].reach * objectiveMultipliers[objective].reach * (duration / 30);
        var engagement = baseEngagement * platformMultipliers[platform].engagement * targetMultipliers[target].engagement * objectiveMultipliers[objective].engagement * (duration / 30);
        var cost = baseCost * platformMultipliers[platform].cost * targetMultipliers[target].cost * objectiveMultipliers[objective].cost;
        var roi = baseRoi * platformMultipliers[platform].roi * targetMultipliers[target].roi * objectiveMultipliers[objective].roi;
        
        // Round results
        reach = Math.round(reach);
        engagement = Math.round(engagement);
        
        return {
            reach: reach,
            engagement: engagement,
            cost: cost,
            roi: roi
        };
    }

    // Filter blog posts by category
    function filterByCategory() {
        var $this = $(this);
        var category = $this.data('category');
        
        // Update active state
        $('.category-pill').removeClass('active');
        $this.addClass('active');
        
        // Filter posts
        if (category) {
            $('.post-card').hide();
            $('.post-card[data-category="' + category + '"]').show();
        } else {
            $('.post-card').show();
        }
    }

    // Check if element is in viewport
    function isElementInViewport($element) {
        var elementTop = $element.offset().top;
        var elementBottom = elementTop + $element.outerHeight();
        var viewportTop = $window.scrollTop();
        var viewportBottom = viewportTop + $window.height();
        
        return elementBottom > viewportTop && elementTop < viewportBottom;
    }

    // Initialize when document is ready
    $(document).ready(function() {
        init();
    });
    
})(jQuery);
