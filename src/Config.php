<?php

namespace FebriHidayan\Laratalk;

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
     * Column avatar for table users
     * 
     * @return string
     */
    public static function userAvatar(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.avatar');
    }

    /**
     * Column bio for table users
     * 
     * @return string
     */
    public static function userBio(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.bio');
    }

    /**
     * Column dark_mode for table users
     * 
     * @return string
     */
    public static function userDarkMode(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.dark_mode');
    }

    /**
     * Column locale for table users
     * 
     * @return string
     */
    public static function userLocale(): string
    {
        return FacadesConfig::get('laratalk.users.migration.columns.locale');
    }

    /**
     * Filesystem disk
     * 
     * @return string
     */
    public static function storageDisk(): string
    {
        return FacadesConfig::get('laratalk.storage.disk');
    }

    /**
     * Storage path
     * 
     * @return string
     */
    public static function storagePath(): string
    {
        return FacadesConfig::get('laratalk.storage.path');
    }

    /**
     * Show all image formats
     * 
     * @return array
     */
    public static function storageImageFormat(): array
    {
        return FacadesConfig::get('laratalk.storage.images.format');
    }

    /**
     * Size for image format
     * 
     * @return int
     */
    public static function storageImageSize(): int
    {
        return FacadesConfig::get('laratalk.storage.images.size');
    }

    /**
     * Show all file formats
     * 
     * @return array
     */
    public static function storageFileFormat(): array
    {
        return FacadesConfig::get('laratalk.storage.files.format');
    }

    /**
     * Size for file format
     * 
     * @return int
     */
    public static function storageFileSize(): int
    {
        return FacadesConfig::get('laratalk.storage.files.size');
    }

    /**
     * Enable or disable group chat feature
     * 
     * @return bool
     */
    public static function groupEnabled(): bool
    {
        return FacadesConfig::get('laratalk.group.enabled');
    }

    /**
     * Determine the maximum group participants
     * 
     * @return int
     */
    public static function groupParticipant(): int
    {
        return FacadesConfig::get('laratalk.group.participant');
    }
}