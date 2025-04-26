/**
 * i18n.js - Internationalization for MDMC Music Ads Theme
 * 
 * This script handles language detection and translation for the theme
 */

(function($) {
    'use strict';

    // i18n object
    var i18n = {
        currentLang: 'fr',
        defaultLang: 'fr',
        supportedLangs: ['fr', 'en', 'es', 'pt'],
        translations: {},
        
        /**
         * Initialize the i18n functionality
         */
        init: function() {
            // Set current language
            this.detectLanguage();
            
            // Load translations
            if (typeof window.i18n !== 'undefined') {
                this.translations = window.i18n;
            }
            
            // Apply translations to the page
            this.translatePage();
            
            // Setup language switcher
            this.setupLanguageSwitcher();
        },
        
        /**
         * Detect the user's language
         */
        detectLanguage: function() {
            // Check for language cookie
            var langCookie = this.getCookie('mdmc_language');
            if (langCookie && this.supportedLangs.indexOf(langCookie) !== -1) {
                this.currentLang = langCookie;
                return;
            }
            
            // Check URL parameter
            var urlLang = this.getUrlParameter('lang');
            if (urlLang && this.supportedLangs.indexOf(urlLang) !== -1) {
                this.currentLang = urlLang;
                this.setCookie('mdmc_language', urlLang, 30);
                return;
            }
            
            // Check browser language
            var browserLang = navigator.language || navigator.userLanguage;
            if (browserLang) {
                browserLang = browserLang.substr(0, 2).toLowerCase();
                if (this.supportedLangs.indexOf(browserLang) !== -1) {
                    this.currentLang = browserLang;
                    this.setCookie('mdmc_language', browserLang, 30);
                    return;
                }
            }
            
            // Default to French
            this.currentLang = this.defaultLang;
        },
        
        /**
         * Get a translation
         */
        translate: function(key) {
            if (!key) return '';
            
            // Check if translation exists for current language
            if (this.translations[key] && this.translations[key][this.currentLang]) {
                return this.translations[key][this.currentLang];
            }
            
            // Fallback to default language
            if (this.translations[key] && this.translations[key][this.defaultLang]) {
                return this.translations[key][this.defaultLang];
            }
            
            // Return the key if no translation found
            return key;
        },
        
        /**
         * Translate all elements with data-i18n attribute
         */
        translatePage: function() {
            var self = this;
            
            // Set html lang attribute
            $('html').attr('lang', this.currentLang);
            
            // Translate elements with data-i18n attribute
            $('[data-i18n]').each(function() {
                var key = $(this).data('i18n');
                var translation = self.translate(key);
                
                if (translation) {
                    $(this).html(translation);
                }
            });
            
            // Translate placeholders
            $('[data-i18n-placeholder]').each(function() {
                var key = $(this).data('i18n-placeholder');
                var translation = self.translate(key);
                
                if (translation) {
                    $(this).attr('placeholder', translation);
                }
            });
            
            // Translate titles
            $('[data-i18n-title]').each(function() {
                var key = $(this).data('i18n-title');
                var translation = self.translate(key);
                
                if (translation) {
                    $(this).attr('title', translation);
                }
            });
            
            // Update language switcher
            $('.language-toggle').text(this.currentLang.toUpperCase());
            $('.language-dropdown a').removeClass('active');
            $('.language-dropdown a[data-lang="' + this.currentLang + '"]').addClass('active');
        },
        
        /**
         * Setup language switcher
         */
        setupLanguageSwitcher: function() {
            var self = this;
            
            // Toggle language dropdown
            $('.language-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var expanded = $(this).attr('aria-expanded') === 'true';
                $(this).attr('aria-expanded', !expanded);
                $('.language-dropdown').toggleClass('active');
            });
            
            // Handle language selection
            $('.language-dropdown a').on('click', function(e) {
                e.preventDefault();
                
                var lang = $(this).data('lang');
                if (lang && self.supportedLangs.indexOf(lang) !== -1) {
                    self.currentLang = lang;
                    self.setCookie('mdmc_language', lang, 30);
                    
                    // Reload page with language parameter
                    var url = new URL(window.location.href);
                    url.searchParams.set('lang', lang);
                    window.location.href = url.toString();
                }
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.language-selector').length) {
                    $('.language-toggle').attr('aria-expanded', 'false');
                    $('.language-dropdown').removeClass('active');
                }
            });
        },
        
        /**
         * Get a cookie value
         */
        getCookie: function(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        
        /**
         * Set a cookie
         */
        setCookie: function(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        },
        
        /**
         * Get URL parameter
         */
        getUrlParameter: function(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }
    };
    
    // Initialize i18n when document is ready
    $(document).ready(function() {
        i18n.init();
        
        // Make i18n available globally
        window.mdmcI18n = i18n;
    });
    
})(jQuery);
