/**
 * Enhanced i18n.js for MDMC Music Ads Blog Theme
 * 
 * Comprehensive internationalization system that handles:
 * - Language detection
 * - UI translation
 * - Content translation
 * - SEO metadata translation
 */

(function($) {
    'use strict';

    // Store translations
    let translations = {};
    
    // Dictionary for automatic content translation
    let dictionary = {};
    
    // Current language
    let currentLang = mdmcI18n.current_lang || 'fr';
    
    /**
     * Initialize i18n functionality
     */
    function init() {
        // Load translations
        loadTranslations();
        
        // Load translation dictionary
        loadDictionary();
        
        // Setup language switcher
        setupLanguageSwitcher();
        
        // Apply translations to the page
        translatePage();
        
        // Setup automatic content translation
        setupContentTranslation();
    }
    
    /**
     * Load translations from JSON file
     */
    function loadTranslations() {
        $.ajax({
            url: mdmcThemeUri + '/assets/js/languages.json',
            dataType: 'json',
            cache: true,
            success: function(data) {
                translations = data;
                translatePage();
            },
            error: function() {
                console.error('Failed to load translations');
            }
        });
    }
    
    /**
     * Load translation dictionary
     */
    function loadDictionary() {
        $.ajax({
            url: mdmcThemeUri + '/assets/js/dictionary.json',
            dataType: 'json',
            cache: true,
            success: function(data) {
                dictionary = data;
                // Apply content translation after dictionary is loaded
                translateContent();
            },
            error: function() {
                console.error('Failed to load dictionary');
            }
        });
    }
    
    /**
     * Setup language switcher functionality
     */
    function setupLanguageSwitcher() {
        // Toggle language dropdown
        $('.language-toggle').on('click', function(e) {
            e.preventDefault();
            $('.language-dropdown').toggleClass('active');
            $(this).attr('aria-expanded', $('.language-dropdown').hasClass('active'));
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.language-selector').length) {
                $('.language-dropdown').removeClass('active');
                $('.language-toggle').attr('aria-expanded', 'false');
            }
        });
        
        // Language selection
        $('.language-dropdown a').on('click', function(e) {
            e.preventDefault();
            const lang = $(this).data('lang');
            
            if (lang && mdmcI18n.languages.includes(lang)) {
                setLanguage(lang);
            }
        });
        
        // Update current language display
        updateCurrentLanguageDisplay();
    }
    
    /**
     * Set the language and update the page
     * 
     * @param {string} lang Language code (fr, en, es, pt)
     */
    function setLanguage(lang) {
        if (lang === currentLang) {
            return;
        }
        
        // Update current language
        currentLang = lang;
        
        // Save language preference via cookie
        document.cookie = 'mdmc_language=' + lang + '; path=/; max-age=' + (86400 * 30); // 30 days
        
        // Update language display
        updateCurrentLanguageDisplay();
        
        // Apply translations
        translatePage();
        
        // Apply content translations
        translateContent();
        
        // Update metadata
        updateMetadata();
    }
    
    /**
     * Update the current language display in the header
     */
    function updateCurrentLanguageDisplay() {
        $('.current-language').text(currentLang.toUpperCase());
        
        // Update active class in dropdown
        $('.language-dropdown a').removeClass('active');
        $(`.language-dropdown a[data-lang="${currentLang}"]`).addClass('active');
    }
    
    /**
     * Translate the page UI elements based on current language
     */
    function translatePage() {
        if (Object.keys(translations).length === 0) {
            return;
        }
        
        // Translate elements with data-i18n attribute
        $('[data-i18n]').each(function() {
            const key = $(this).data('i18n');
            if (translations[key] && translations[key][currentLang]) {
                $(this).html(translations[key][currentLang]);
            }
        });
        
        // Translate placeholders with data-i18n-placeholder
        $('[data-i18n-placeholder]').each(function() {
            const key = $(this).data('i18n-placeholder');
            if (translations[key] && translations[key][currentLang]) {
                $(this).attr('placeholder', translations[key][currentLang]);
            }
        });
        
        // Translate aria-labels with data-i18n-aria
        $('[data-i18n-aria]').each(function() {
            const key = $(this).data('i18n-aria');
            if (translations[key] && translations[key][currentLang]) {
                $(this).attr('aria-label', translations[key][currentLang]);
            }
        });
    }
    
    /**
     * Setup automatic content translation
     */
    function setupContentTranslation() {
        // Add data attributes to content elements for translation
        $('.entry-title, .entry-content p, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content li, .entry-summary').each(function() {
            // Skip elements that already have data-i18n attribute
            if ($(this).attr('data-i18n')) {
                return;
            }
            
            // Add data-content-lang attribute to mark content for translation
            $(this).attr('data-content-lang', 'fr');
            
            // Store original content for translation
            $(this).attr('data-original-content', $(this).html());
        });
        
        // Apply content translation
        translateContent();
    }
    
    /**
     * Translate content based on dictionary
     */
    function translateContent() {
        if (Object.keys(dictionary).length === 0) {
            return;
        }
        
        // Skip if current language is French (source language)
        if (currentLang === 'fr') {
            // Restore original content
            $('[data-content-lang]').each(function() {
                $(this).html($(this).attr('data-original-content'));
            });
            return;
        }
        
        // Translate content elements
        $('[data-content-lang]').each(function() {
            const originalContent = $(this).attr('data-original-content');
            if (!originalContent) {
                return;
            }
            
            // Translate content using dictionary
            let translatedContent = translateTextWithDictionary(originalContent, 'fr', currentLang);
            
            // Update element with translated content
            $(this).html(translatedContent);
        });
    }
    
    /**
     * Translate text using dictionary
     * 
     * @param {string} text Text to translate
     * @param {string} sourceLang Source language
     * @param {string} targetLang Target language
     * @return {string} Translated text
     */
    function translateTextWithDictionary(text, sourceLang, targetLang) {
        if (!dictionary[sourceLang] || !dictionary[targetLang]) {
            return text;
        }
        
        let translatedText = text;
        
        // Replace words and phrases based on dictionary
        for (const [sourceWord, translations] of Object.entries(dictionary[sourceLang])) {
            if (translations[targetLang]) {
                // Create a regular expression to match the word with word boundaries
                const regex = new RegExp('\\b' + sourceWord + '\\b', 'gi');
                translatedText = translatedText.replace(regex, translations[targetLang]);
            }
        }
        
        return translatedText;
    }
    
    /**
     * Update metadata for SEO based on current language
     */
    function updateMetadata() {
        // Get page metadata
        const pageData = mdmcI18n.pageData || {};
        
        // Update meta description
        if (pageData.meta_description && pageData.meta_description[currentLang]) {
            $('meta[name="description"]').attr('content', pageData.meta_description[currentLang]);
            $('meta[property="og:description"]').attr('content', pageData.meta_description[currentLang]);
        }
        
        // Update meta keywords
        if (pageData.meta_keywords && pageData.meta_keywords[currentLang]) {
            $('meta[name="keywords"]').attr('content', pageData.meta_keywords[currentLang]);
        }
        
        // Update og:locale
        $('meta[property="og:locale"]').attr('content', getLocaleFromCode(currentLang));
    }
    
    /**
     * Get locale from language code
     * 
     * @param {string} code Language code
     * @return {string} Locale
     */
    function getLocaleFromCode(code) {
        const locales = {
            'fr': 'fr_FR',
            'en': 'en_US',
            'es': 'es_ES',
            'pt': 'pt_PT'
        };
        
        return locales[code] || 'fr_FR';
    }
    
    /**
     * Get translation for a specific key
     * 
     * @param {string} key Translation key
     * @return {string} Translated text or key if not found
     */
    function getTranslation(key) {
        if (translations[key] && translations[key][currentLang]) {
            return translations[key][currentLang];
        }
        
        // Fallback to French
        if (translations[key] && translations[key]['fr']) {
            return translations[key]['fr'];
        }
        
        return key;
    }
    
    // Make getTranslation available globally
    window.mdmcGetTranslation = getTranslation;
    
    // Initialize when document is ready
    $(document).ready(init);
    
})(jQuery);
