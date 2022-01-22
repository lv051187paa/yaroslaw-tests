<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api;

class SettingsApi
{

    public array $admin_pages = array();

    public array $admin_subpages = array();

    public array $settings = array();

    public array $sections = array();

    public array $fields = array();

    public function register()
    {
        if (!empty( $this->admin_pages )) {
            add_action( 'admin_menu', array($this, 'addAdminMenu') );
        }

        if (!empty( $this->settings )) {
            add_action( 'admin_init', array($this, 'registerCustomFields') );
        }
    }

    public function addPages( array $pages ): static
    {
        $this->admin_pages = $pages;

        return $this;
    }

    public function addSubPages( array $pages ): static
    {
        $this->admin_subpages = array_merge( $this->admin_subpages, $pages );

        return $this;
    }

    public function setSettings( array $settings ): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function setSections( array $sections ): static
    {
        $this->sections = $sections;

        return $this;
    }

    public function setFields( array $fields ): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function addAdminMenu()
    {
        foreach ( $this->admin_pages as $page ) {
            add_menu_page(
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback'],
                $page['icon_url'],
                $page['position']
            );
        }

        foreach ( $this->admin_subpages as $page ) {
            add_submenu_page(
                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback']
            );
        }
    }

    public function withSubpage( string $title = null ): static
    {
        if (empty( $this->admin_pages )) {
            return $this;
        }

        $admin_page = $this->admin_pages[0];

        $subpage = array(
            array(
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'],
                'menu_title' => ($title) ? $title : $admin_page['menu_title'],
                'capability' => $admin_page['capability'],
                'menu_slug' => $admin_page['menu_slug'],
                'callback' => $admin_page['callback'],
            )
        );

        $this->admin_subpages = $subpage;

        return $this;
    }

    public function registerCustomFields()
    {
        foreach ( $this->settings as $setting ) {
            register_setting( $setting["option_group"], $setting["option_name"],
	            $setting["callback"] ?? '' );
        }

        foreach ( $this->sections as $section ) {
            add_settings_section( $section["id"], $section["title"],
	            $section["callback"] ?? '', $section["page"] );
        }

        foreach ( $this->fields as $field ) {
            add_settings_field( $field["id"], $field["title"], $field["callback"] ?? '',
                $field["page"], $field["section"], $field["callback"] ?? '' );
        }
    }
}