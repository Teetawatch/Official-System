<?php
/* ==========================================================================
 * NASS – PROFESSIONAL MEGA MENU (Smart Layout) - FIXED VERSION v2.0
 *  - เมนูหลักมีแค่เมนูย่อยชั้นเดียว -> Simple (แนวตั้ง)
 *  - มีเมนูย่อยที่มีลูกต่ออีกชั้น -> Mega (แนวนอน)
 *  - รองรับ Font Awesome icon ผ่าน CSS Classes ของเมนู
 *  - แก้ไขปัญหา Scroll และ Topbar overlap
 * ========================================================================= */

/**
 * 1. Register Menu Location
 */
add_action( 'init', function() {
    register_nav_menus( [
        'nass_mega_menu_location' => __( 'NASS Mega Menu (หลัก)', 'nass-mega-menu' ),
    ] );
} );

/**
 * 2. Shortcode
 * ใช้ในเพจ: [nass_mega_menu logo_url="https://.../logo.png" sticky="true" topbar_height="60"]
 */
add_shortcode( 'nass_mega_menu', 'nass_render_mega_menu_system' );

/**
 * 3. Custom Walker Class
 */
if ( ! class_exists( 'NASS_Mega_Menu_Walker' ) ) :

class NASS_Mega_Menu_Walker extends Walker_Nav_Menu {

    /**
     * Logic:
     * - ถ้าเมนูหลัก (depth 0) มีเมนูย่อยที่มีลูกต่อ => nass-mode-mega (แนวนอน)
     * - ถ้ามีแค่เมนูย่อยชั้นเดียว          => nass-mode-simple (แนวตั้ง)
     * Override:
     * - "nass-force-mega" / "nass-force-simple" ที่เมนูหลัก
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element ) {
            return;
        }

        $id_field = $this->db_fields['id'];
        $id       = $element->$id_field;

        $element_classes = isset( $element->classes ) ? (array) $element->classes : [];

        $has_children = isset( $children_elements[ $id ] ) && ! empty( $children_elements[ $id ] );

        if ( $has_children ) {
            $element->classes[] = 'has-children';

            if ( $depth === 0 ) {
                if ( in_array( 'nass-force-mega', $element_classes, true ) ) {
                    $element->classes[] = 'nass-mode-mega';
                } elseif ( in_array( 'nass-force-simple', $element_classes, true ) ) {
                    $element->classes[] = 'nass-mode-simple';
                } else {
                    // auto detect จากโครงสร้างเมนู
                    $children       = $children_elements[ $id ];
                    $has_grandchild = false;

                    foreach ( $children as $child ) {
                        $child_id = $child->$id_field;
                        if ( isset( $children_elements[ $child_id ] ) && ! empty( $children_elements[ $child_id ] ) ) {
                            $has_grandchild = true;
                            break;
                        }
                    }

                    if ( $has_grandchild ) {
                        $element->classes[] = 'nass-mode-mega';
                    } else {
                        $element->classes[] = 'nass-mode-simple';
                    }
                }
            }
        }

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    /**
     * สร้าง <ul> ของ Sub-menu
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent  = str_repeat( "\t", $depth );
        $classes = [ 'sub-menu' ];

        if ( $depth === 0 ) {
            $classes[] = 'nass-dropdown-panel';
        }

        $class_names = implode( ' ', $classes );
        $output     .= "\n$indent<ul class=\"" . esc_attr( $class_names ) . "\">\n";
    }

    /**
     * สร้าง <li> + <a> ของแต่ละรายการเมนู
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? [] : (array) $item->classes;

        // เพิ่ม class พื้นฐานของเราเข้าไปก่อน
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'lvl-' . $depth;

        // เก็บชุด class เดิมไว้ใช้หา icon และตรวจสอบ has-children (ก่อนจะลบออกจาก <li>)
        $all_classes = $classes;

        // ===== Icon Handling (รองรับ Font Awesome หลายคลาส) =====
        $icon_html    = '';
        $icon_classes = array_filter(
            $all_classes,
            function( $class ) {
                return preg_match( '/^(fa|fas|far|fab|fal|fad)(-|$)/', $class )
                    || strpos( $class, 'fa-' ) === 0;
            }
        );

        // สร้าง <i> icon ถ้ามีคลาสของ Font Awesome
        if ( ! empty( $icon_classes ) ) {
            $icon_html = '<i class="' . esc_attr( implode( ' ', $icon_classes ) ) . ' nass-menu-icon"></i> ';
            // *** สำคัญ: เอา icon-classes ออกจาก class ของ <li> ไม่ให้ <li> ติด fa-* ***
            $classes = array_diff( $classes, $icon_classes );
        }

        // สร้าง class ของ <li> (หลังจากลบ fa-* ออกแล้ว)
        $class_names = join(
            ' ',
            apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args )
        );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        // ===== Attributes ของ <a> =====
        $atts           = [];
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '#';

        // FIX: ใช้ $all_classes แทน $classes เพื่อตรวจสอบ has-children
        if ( in_array( 'has-children', $all_classes, true ) ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title       = apply_filters( 'the_title', $item->title, $item->ID );
        $item_output = isset( $args->before ) ? $args->before : '';

        $item_output .= '<a' . $attributes . '>';
        $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' );
        // ใส่ icon แล้วตามด้วยตัวหนังสือปกติ
        $item_output .= $icon_html . '<span class="nass-text">' . $title . '</span>';
        $item_output .= ( isset( $args->link_after ) ? $args->link_after : '' );

        // ลูกศรเฉพาะ Desktop Level 0 ที่มีลูก (FIX: ใช้ $all_classes)
        if ( in_array( 'has-children', $all_classes, true ) && $depth === 0 ) {
            $item_output .= ' <i class="fa-solid fa-chevron-down nass-caret" aria-hidden="true"></i>';
        }

        $item_output .= '</a>';

        // ปุ่ม toggle บน mobile เฉพาะเมนูที่มีลูก (FIX: ใช้ $all_classes)
        if ( in_array( 'has-children', $all_classes, true ) ) {
            $item_output .= '<button class="nass-mobile-toggle-btn" type="button" aria-label="Toggle submenu"><i class="fa-solid fa-chevron-right"></i></button>';
        }

        $item_output .= isset( $args->after ) ? $args->after : '';
        $output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

endif; // class_exists

/**
 * 4. Render Function (Shortcode Output)
 */
function nass_render_mega_menu_system( $atts ) {
    static $nass_mega_menu_loaded = false;

    $a = shortcode_atts(
        [
            'logo_url'       => 'https://nass.ac.th/wp-content/uploads/2025/10/NSRS.webp',
            'sticky'         => 'true',
            'topbar_height'  => '60',  // ความสูงของ topbar (px) - ปรับตาม topbar จริง
            'hide_topbar_on_scroll' => 'false', // ซ่อน topbar เมื่อ scroll
        ],
        $atts
    );

    $logo_src        = esc_url( $a['logo_url'] );
    $topbar_height   = intval( $a['topbar_height'] );
    $hide_topbar     = $a['hide_topbar_on_scroll'] === 'true';

    ob_start();
    ?>

    <?php if ( ! $nass_mega_menu_loaded ) : $nass_mega_menu_loaded = true; ?>
        <!-- Preconnect for better performance -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        <style>
            /* ========================================
               NASS Mega Menu - CSS Variables
            ======================================== */
            :root {
                --nm-height: 90px;
                --nm-topbar-height: <?php echo $topbar_height; ?>px;
                --nm-bg: transparent;
                --nm-bg-scrolled: rgba(255, 255, 255, 0.98);
                --nm-text: #333333;
                --nm-accent: #FBC02D;
                --nm-navy: #0A1E39;
                --nm-font: 'Kanit', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                --nm-transition: 0.3s ease;
                --nm-shadow: none;
                --nm-dropdown-shadow: 0 12px 40px rgba(10, 30, 57, 0.18);
            }

            /* ========================================
               Main Wrapper
            ======================================== */
            .nass-mega-wrapper {
                background: var(--nm-bg);
                font-family: var(--nm-font);
                position: relative;
                z-index: 10100; /* สูงกว่า topbar (10050) */
                width: 100%;
                transition: background var(--nm-transition), box-shadow var(--nm-transition), top var(--nm-transition);
            }

            .nass-mega-wrapper.is-sticky {
                position: -webkit-sticky;
                position: sticky;
                top: var(--nm-topbar-height); /* อยู่ใต้ topbar */
                left: 0;
                right: 0;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            /* เมื่อ scroll และ hide topbar - menu ขยับขึ้นมาอยู่ top: 0 */
            .nass-mega-wrapper.is-sticky.hide-topbar-mode {
                top: 0;
            }

            /* Scrolled state */
            .nass-mega-wrapper.is-sticky.scrolled {
                background: var(--nm-bg-scrolled) !important;
                box-shadow: none !important;
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
            }

            /* ========================================
               Container
            ======================================== */
            .nass-mega-container {
                width: 100%;
                max-width: 1320px;
                padding: 0 32px;
                margin: 0 auto;
                height: var(--nm-height);
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-sizing: border-box;
                gap: 15px;
            }

            /* ========================================
               Brand / Logo
            ======================================== */
            .nass-brand {
                display: flex;
                align-items: center;
                height: 100%;
                flex-shrink: 0;
                margin-right: 24px;
            }

            .nass-brand a {
                display: flex;
                align-items: center;
                text-decoration: none;
            }

            .nass-brand img {
                height: 90px;
                width: auto;
                display: block;
                transition: transform 0.2s ease, height 0.3s ease;
            }

            .nass-brand img:hover {
                transform: scale(1.02);
            }

            /* Logo เล็กลงเมื่อ scroll */
            .nass-mega-wrapper.scrolled .nass-brand img {
                height: 70px;
            }

            /* ========================================
               Desktop Navigation
            ======================================== */
            .nass-desktop-nav {
                flex-grow: 1;
                height: 100%;
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }

            ul.nass-root-menu {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                height: 100%;
                align-items: center;
                gap: 5px;
            }

            /* ========================================
               Level 0 Menu Items
            ======================================== */
            li.lvl-0 {
                height: 100%;
                display: flex;
                align-items: center;
                flex-shrink: 0;
            }

            li.lvl-0 > a {
                position: relative;
                padding: 0 12px;
                color: var(--nm-text);
                text-decoration: none !important;
                font-weight: 500;
                font-size: 1rem;
                height: 100%;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: color 0.2s ease, transform 0.15s ease;
                white-space: nowrap;
            }

            li.lvl-0 > a::after {
                content: '';
                position: absolute;
                left: 10px;
                right: 10px;
                bottom: 14px;
                height: 3px;
                border-radius: 999px;
                background: var(--nm-accent);
                opacity: 0;
                transform: scaleX(0.4);
                transform-origin: center;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            li.lvl-0:hover > a,
            ul.nass-root-menu > li.current-menu-item > a,
            ul.nass-root-menu > li.current-menu-ancestor > a {
                color: var(--nm-navy);
            }

            li.lvl-0:hover > a::after,
            ul.nass-root-menu > li.current-menu-item > a::after,
            ul.nass-root-menu > li.current-menu-ancestor > a::after {
                opacity: 1;
                transform: scaleX(1);
            }

            /* ========================================
               Caret / Arrow Icon
            ======================================== */
            .nass-caret {
                font-size: 0.75em;
                opacity: 0.6;
                margin-left: 2px;
                transition: transform 0.3s ease, opacity 0.2s ease;
            }

            li.lvl-0:hover .nass-caret {
                transform: rotate(180deg);
                opacity: 1;
            }

            /* ========================================
               Menu Icon
            ======================================== */
            .nass-menu-icon {
                font-size: 0.95em;
                opacity: 0.9;
                margin-right: 2px;
            }

            /* ========================================
               Mobile Toggle Button (Hidden on Desktop)
            ======================================== */
            .nass-mobile-toggle-btn {
                display: none;
            }

            .nass-mobile-toggle {
                display: none;
            }

            /* ========================================
               Dropdown Panel - Base Styles
            ======================================== */
            ul.nass-dropdown-panel {
                position: absolute;
                top: 100%;
                background: #ffffff;
                box-shadow: var(--nm-dropdown-shadow);
                border-top: 3px solid var(--nm-accent);
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: opacity 0.2s ease-out, transform 0.2s ease-out, visibility 0.2s;
                z-index: 100;
                list-style: none;
                padding: 0;
                margin: 0;
            }

            li.lvl-0:hover > ul.nass-dropdown-panel {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            /* ========================================
               Mega Mode (Multi-column Layout)
            ======================================== */
            li.nass-mode-mega {
                position: static !important;
            }

            li.nass-mode-mega > ul.nass-dropdown-panel {
                left: 0;
                width: 100%;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                padding: 30px 40px;
                box-sizing: border-box;
            }

            li.nass-mode-mega > ul > li {
                flex: 1;
                min-width: 220px;
                max-width: 280px;
                padding: 0 25px;
                border-right: 1px solid #f0f0f0;
            }

            li.nass-mode-mega > ul > li:last-child {
                border-right: none;
            }

            li.nass-mode-mega > ul > li > a {
                font-weight: 700;
                color: var(--nm-navy);
                font-size: 1.05rem;
                margin-bottom: 14px;
                display: block;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 10px;
                text-decoration: none;
            }

            li.nass-mode-mega ul.sub-menu li a {
                padding: 6px 0;
                display: block;
                color: #555;
                text-decoration: none;
                font-size: 0.95rem;
                transition: color 0.15s ease, transform 0.15s ease;
            }

            li.nass-mode-mega ul.sub-menu li a:hover {
                color: var(--nm-navy);
                transform: translateX(5px);
            }

            /* ========================================
               Simple Mode (Single Column Dropdown)
            ======================================== */
            li.nass-mode-simple {
                position: relative !important;
            }

            li.nass-mode-simple > ul.nass-dropdown-panel {
                left: 0;
                width: max-content;
                min-width: 220px;
                max-width: 320px;
                display: flex;
                flex-direction: column;
                padding: 10px 0;
                border-radius: 0 0 8px 8px;
            }

            li.nass-mode-simple > ul > li {
                width: 100%;
                border: none;
                padding: 0;
            }

            li.nass-mode-simple > ul > li > a {
                padding: 10px 20px;
                display: block;
                color: #444;
                font-size: 0.95rem;
                text-decoration: none;
                transition: background 0.15s ease, color 0.15s ease;
                border: none !important;
                border-bottom: none !important;
                box-shadow: none !important;
                background-image: none !important;
            }

            li.nass-mode-simple > ul > li > a:hover {
                background: #f5f7fa;
                color: var(--nm-navy);
            }

            /* ========================================
               Focus Visible (Accessibility)
            ======================================== */
            .nass-desktop-nav a:focus-visible,
            .nass-mobile-toggle:focus-visible,
            .nass-mobile-toggle-btn:focus-visible {
                outline: 2px solid var(--nm-accent);
                outline-offset: 2px;
            }

            /* ========================================
               RESPONSIVE - Tablet & Mobile (max-width: 1200px)
            ======================================== */
            @media (max-width: 1200px) {
                :root {
                    --nm-topbar-height: 0px; /* Mobile ไม่ต้อง offset */
                }

                .nass-mega-wrapper {
                    background: #ffffff;
                }

                .nass-mega-wrapper.is-sticky {
                    top: 0; /* Mobile sticky ที่ top 0 */
                }

                .nass-mega-container {
                    height: 80px;
                    padding: 0 20px;
                    max-width: 100%;
                    gap: 16px;
                }

                .nass-brand img {
                    height: 64px;
                }

                .nass-mega-wrapper.scrolled .nass-brand img {
                    height: 56px;
                }

                /* Desktop Nav becomes Mobile Drawer */
                .nass-desktop-nav {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    background: #ffffff;
                    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
                    border-top: 1px solid #eee;
                    max-height: 85vh;
                    overflow-y: auto;
                    justify-content: flex-start;
                    padding-bottom: 20px;
                    z-index: 9998;
                }

                .nass-desktop-nav.active {
                    display: block;
                }

                ul.nass-root-menu {
                    flex-direction: column;
                    height: auto;
                    width: 100%;
                    gap: 0;
                }

                li.lvl-0 {
                    height: auto;
                    flex-direction: column;
                    width: 100%;
                    border-bottom: 1px solid #eee;
                    position: relative !important;
                }

                li.lvl-0 > a {
                    width: 100%;
                    padding: 15px 18px;
                    justify-content: flex-start;
                    height: auto;
                }

                li.lvl-0 > a::after {
                    display: none;
                }

                /* Disable hover dropdown on mobile */
                li.lvl-0:hover > ul.nass-dropdown-panel {
                    display: none;
                }

                li.nass-mode-simple > a::after {
                    display: none !important;
                }

                /* Dropdown Panel - Mobile Styles */
                ul.nass-dropdown-panel {
                    position: static;
                    box-shadow: none;
                    border: none;
                    border-top: none;
                    padding: 0;
                    opacity: 1;
                    visibility: visible;
                    transform: none;
                    display: none;
                    width: 100% !important;
                    background: #f9fafb;
                    flex-direction: column !important;
                    max-width: none !important;
                }

                ul.nass-dropdown-panel.show {
                    display: block;
                }

                /* Mobile Menu Items */
                li.nass-mode-mega > ul > li,
                li.nass-mode-simple > ul > li {
                    width: 100%;
                    border: none;
                    padding: 0;
                    max-width: none;
                }

                li.nass-mode-mega > ul > li > a,
                li.nass-mode-simple > ul > li > a {
                    padding: 12px 40px;
                    font-weight: 500;
                    font-size: 0.95rem;
                    border-bottom: 1px solid #eee;
                    margin: 0;
                }

                /* Simple on mobile: no border */
                li.nass-mode-simple > ul > li > a {
                    border-bottom: none !important;
                }

                /* Mobile Toggle Button */
                .nass-mobile-toggle {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    background: transparent;
                    border: none;
                    font-size: 1.5rem;
                    color: var(--nm-navy);
                    cursor: pointer;
                    padding: 8px;
                    transition: transform 0.2s ease;
                }

                .nass-mobile-toggle:hover {
                    transform: scale(1.1);
                }

                /* Submenu Toggle Button */
                .nass-mobile-toggle-btn {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: absolute;
                    top: 0;
                    right: 0;
                    width: 50px;
                    height: 50px;
                    background: transparent;
                    border: none;
                    border-left: 1px solid #eee;
                    cursor: pointer;
                    transition: background 0.15s ease;
                }

                .nass-mobile-toggle-btn:hover {
                    background: #f5f5f5;
                }

                .nass-mobile-toggle-btn i {
                    transition: transform 0.3s ease;
                }

                /* Hide Desktop Caret */
                .nass-caret {
                    display: none;
                }
            }

            /* ========================================
               RESPONSIVE - Mobile (max-width: 768px)
            ======================================== */
            @media (max-width: 768px) {
                .nass-mega-container {
                    padding: 0 16px;
                }

                .nass-brand img {
                    height: 56px;
                }

                li.nass-mode-mega > ul > li > a,
                li.nass-mode-simple > ul > li > a {
                    padding: 10px 30px;
                }
            }

            /* ========================================
               RESPONSIVE - Small Mobile (max-width: 480px)
            ======================================== */
            @media (max-width: 480px) {
                :root {
                    --nm-height: 70px;
                }

                .nass-mega-container {
                    height: 70px;
                    padding: 0 12px;
                }

                .nass-brand img {
                    height: 48px;
                }
            }

            /* ========================================
               Topbar Auto-hide on Scroll (Optional)
            ======================================== */
            .nass-topbar-hidden {
                transform: translateY(-100%) !important;
                opacity: 0 !important;
            }
        </style>

        <script>
        (function() {
            'use strict';

            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.querySelector('.nass-mega-wrapper');
                const menuBtn = document.getElementById('nass-mobile-btn');
                const nav     = document.getElementById('nass-nav');

                // Configuration จาก PHP
                const hideTopbarOnScroll = <?php echo $hide_topbar ? 'true' : 'false'; ?>;
                const topbarHeight = <?php echo $topbar_height; ?>;

                // ========================================
                // Auto-detect Topbar
                // ========================================
                const topbar = document.querySelector('.nass-topbar, .nass-top-header, .kadence-sticky-header, [class*="top-bar"], [class*="topbar"]');

                // ========================================
                // Sticky Header - Scroll Handler with Throttle
                // ========================================
                if (wrapper && wrapper.classList.contains('is-sticky')) {
                    let ticking = false;
                    let lastScrollY = 0;

                    const updateScrollState = function() {
                        const scrolled = lastScrollY > 10;

                        // Add/remove scrolled class
                        wrapper.classList.toggle('scrolled', scrolled);

                        // Handle topbar hiding (optional)
                        if (hideTopbarOnScroll && topbar) {
                            if (lastScrollY > topbarHeight) {
                                topbar.classList.add('nass-topbar-hidden');
                                wrapper.classList.add('hide-topbar-mode');
                            } else {
                                topbar.classList.remove('nass-topbar-hidden');
                                wrapper.classList.remove('hide-topbar-mode');
                            }
                        }

                        ticking = false;
                    };

                    const onScroll = function() {
                        lastScrollY = window.scrollY || window.pageYOffset;
                        if (!ticking) {
                            window.requestAnimationFrame(updateScrollState);
                            ticking = true;
                        }
                    };

                    // Initial check on load
                    lastScrollY = window.scrollY || window.pageYOffset;
                    updateScrollState();

                    // Add scroll listener with passive option for better performance
                    window.addEventListener('scroll', onScroll, { passive: true });
                }

                // ========================================
                // Mobile Menu Toggle
                // ========================================
                if (menuBtn && nav) {
                    menuBtn.addEventListener('click', function() {
                        nav.classList.toggle('active');
                        const icon = menuBtn.querySelector('i');

                        if (icon) {
                            icon.classList.toggle('fa-bars');
                            icon.classList.toggle('fa-xmark');
                        }

                        // Update aria-expanded
                        const isExpanded = nav.classList.contains('active');
                        menuBtn.setAttribute('aria-expanded', isExpanded);

                        // Close all submenus when closing main nav
                        if (!isExpanded) {
                            closeAllSubmenus();
                        }
                    });
                }

                // ========================================
                // Submenu Toggle Buttons
                // ========================================
                const toggleBtns = document.querySelectorAll('.nass-mobile-toggle-btn');
                
                toggleBtns.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const parentLi = this.closest('li');
                        const sub = parentLi ? parentLi.querySelector(':scope > ul.nass-dropdown-panel') : null;

                        if (sub) {
                            const isOpen = sub.classList.toggle('show');
                            const icon   = this.querySelector('i');

                            if (icon) {
                                icon.style.transform = isOpen ? 'rotate(90deg)' : 'rotate(0deg)';
                            }

                            const link = parentLi.querySelector(':scope > a[aria-expanded]');
                            if (link) {
                                link.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                            }
                        }
                    });
                });

                // ========================================
                // Helper Functions
                // ========================================
                function closeAllSubmenus() {
                    if (!nav) return;

                    nav.querySelectorAll('ul.nass-dropdown-panel.show').forEach(function(ul) {
                        ul.classList.remove('show');
                    });

                    nav.querySelectorAll('.nass-mobile-toggle-btn i').forEach(function(i) {
                        i.style.transform = 'rotate(0deg)';
                    });

                    nav.querySelectorAll('a[aria-expanded="true"]').forEach(function(a) {
                        a.setAttribute('aria-expanded', 'false');
                    });
                }

                // ========================================
                // Click Outside to Close Mobile Menu
                // ========================================
                document.addEventListener('click', function(e) {
                    if (nav && nav.classList.contains('active')) {
                        const isClickInside = wrapper.contains(e.target);
                        if (!isClickInside) {
                            nav.classList.remove('active');
                            menuBtn.setAttribute('aria-expanded', 'false');
                            const icon = menuBtn ? menuBtn.querySelector('i') : null;
                            if (icon) {
                                icon.classList.add('fa-bars');
                                icon.classList.remove('fa-xmark');
                            }
                            closeAllSubmenus();
                        }
                    }
                });

                // ========================================
                // ESC Key to Close Mobile Menu
                // ========================================
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && nav && nav.classList.contains('active')) {
                        nav.classList.remove('active');
                        menuBtn.setAttribute('aria-expanded', 'false');
                        const icon = menuBtn ? menuBtn.querySelector('i') : null;
                        if (icon) {
                            icon.classList.add('fa-bars');
                            icon.classList.remove('fa-xmark');
                        }
                        closeAllSubmenus();
                    }
                });

            });
        })();
        </script>
    <?php endif; ?>

    <header class="nass-mega-wrapper <?php echo ( $a['sticky'] === 'true' ) ? 'is-sticky' : ''; ?>">
        <div class="nass-mega-container">
            <div class="nass-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
            </div>

            <button class="nass-mobile-toggle" id="nass-mobile-btn" type="button" aria-label="Toggle navigation" aria-expanded="false">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>

            <nav class="nass-desktop-nav" id="nass-nav" aria-label="Main navigation">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'nass_mega_menu_location',
                    'container'      => false,
                    'menu_class'     => 'nass-root-menu',
                    'walker'         => new NASS_Mega_Menu_Walker(),
                    'depth'          => 3,
                ] );
                ?>
            </nav>
        </div>
    </header>

    <?php
    return ob_get_clean();
}
