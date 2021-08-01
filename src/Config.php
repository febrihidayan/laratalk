<?php

namespace Laratalk;

use Illuminate\Support\Facades\Config as FacadesConfig;

class Config
{
    /**
     * App name
     * 
     * @return string
     */
    public static function name(): string
    {
        return FacadesConfig::get('laratalk.name');
    }

    /**
     * Base route
     * 
     * @return string
     */
    public static function path(): string
    {
        return FacadesConfig::get('laratalk.path');
    }

    /**
     * Broadcasting connections pusher (laravel echo)
     * 
     * @return array
     */
    public static function pusher(): array
    {
        return FacadesConfig::get('broadcasting.connections.pusher');
    }

    /**
     * Table groups
     * 
     * @param string $column
     * @return string
     */
    public static function groups(string $column = ''): string
    {
        $column = $column ? ".$column" : $column;
        
        return FacadesConfig::get('laratalk.tables.groups') . $column;
    }

    /**
     * Table group_user
     * 
     * @param string $column
     * @return string
     */
    public static function groupUser(string $column = ''): string
    {
        $column = $column ? ".$column" : $column;
        
        return FacadesConfig::get('laratalk.tables.group_user') . $column;
    }

    /**
     * Table messages
     * 
     * @param string $column
     * @return string
     */
    public static function messages(string $column = ''): string
    {
        $column = $column ? ".$column" : $column;
        
        return FacadesConfig::get('laratalk.tables.messages') . $column;
    }

    /**
     * Table message_recipient
     * 
     * @param string $column
     * @return string
     */
    public static function messageRecipient(string $column = ''): string
    {
        $column = $column ? ".$column" : $column;
        
        return FacadesConfig::get('laratalk.tables.message_recipient') . $column;
    }

    /**
     * Namespace user model
     * 
     * @return string
     */
    public static function userModel(): string
    {
        return FacadesConfig::get('laratalk.users.model');
    }

    /**
     * Avatar from Gravatar for user
     * 
     * @return bool
     */
    public static function userGravatar(): bool
    {
        return FacadesConfig::get('laratalk.users.gravatar');
    }

    /**
     * Table users
     * 
     * @param string $column
     * @return string
     */
    public static function users(string $column = ''): string
    {
        $column = $column ? ".$column" : $column;
        
        return FacadesConfig::get('laratalk.users.migration.table') . $column;
    }

    /**
     * Column avatar for table users
     * 
     * @return string
     */
    public static function avatar(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.avatar');
    }

    /**
     * Column dark_mode for table users
     * 
     * @return string
     */
    public static function darkMode(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.dark_mode');
    }

    /**
     * Column locale for table users
     * 
     * @return string
     */
    public static function locale(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.locale');
    }
}