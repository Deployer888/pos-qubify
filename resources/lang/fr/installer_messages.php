<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => 'Installateur de Laravel',
    'next' => 'Suivant',
    'back' => 'Précédent', // Corrected spelling
    'finish' => 'Installer',
    'forms' => [
        'errorTitle' => 'Les erreurs suivantes sont survenues :', // Added colon and spacing
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'title'   => 'Bienvenue dans l’installateur...',
        'message' => 'Assistant d\'installation et de configuration facile.',
        'next'    => 'Vérifier les prérequis',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Étape 1 | Prérequis du serveur',
        'title' => 'Prérequis du serveur',
        'next'    => 'Vérifier les Permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Étape 2 | Permissions',
        'title' => 'Permissions',
        'next' => 'Configurer l\'environnement', // Corrected spelling
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Étape 3 | Paramètres d\'environnement',
            'title' => 'Paramètres d\'environnement',
            'desc' => 'Veuillez sélectionner comment vous souhaitez configurer le fichier <code>.env</code> de l\'application.', // Corrected wording
            'wizard-button' => 'Configuration via l\'assistant de formulaire', // Slightly rephrased
            'classic-button' => 'Éditeur de texte classique',
        ],
        'wizard' => [
            'templateTitle' => 'Étape 3 | Paramètres d\'environnement | Assistant guidé',
            'title' => 'Assistant <code>.env</code> Guidé', // Corrected spelling
            'tabs' => [
                'environment' => 'Environnement',
                'database' => 'Base de données', // Changed to plural
                'application' => 'Application',
            ],
            'form' => [
                'name_required' => 'Un nom d\'environnement est requis.',
                'app_name_label' => 'Nom de l\'application', // Added 'de l\'application'
                'app_name_placeholder' => 'Nom de l\'application', // Added 'de l\'application'
                'app_environment_label' => 'Environnement de l\'application', // Added 'de l\'application'
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Développement', // Corrected spelling
                'app_environment_label_qa' => 'QA', // Keep as is
                'app_environment_label_production' => 'Production',
                'app_environment_label_other' => 'Autre',
                'app_environment_placeholder_other' => 'Entrez votre environnement...',
                'app_debug_label' => 'Débogage de l\'application', // Added 'de l\'application'
                'app_debug_label_true' => 'Vrai',
                'app_debug_label_false' => 'Faux',
                'app_log_level_label' => 'Niveau de log de l\'application', // Added 'de l\'application'
                'app_log_level_label_debug' => 'debug', // Keep technical terms
                'app_log_level_label_info' => 'info', // Keep technical terms
                'app_log_level_label_notice' => 'notice', // Keep technical terms
                'app_log_level_label_warning' => 'warning', // Keep technical terms
                'app_log_level_label_error' => 'error', // Keep technical terms
                'app_log_level_label_critical' => 'critical', // Keep technical terms
                'app_log_level_label_alert' => 'alert', // Keep technical terms
                'app_log_level_label_emergency' => 'emergency', // Keep technical terms
                'app_url_label' => 'URL de l\'application', // Added 'de l\'application'
                'app_url_placeholder' => 'URL de l\'application', // Added 'de l\'application'
                'db_connection_label' => 'Connexion à la base de données', // Added 'à la base de données'
                'db_connection_label_mysql' => 'mysql', // Keep technical terms
                'db_connection_label_sqlite' => 'sqlite', // Keep technical terms
                'db_connection_label_pgsql' => 'pgsql', // Keep technical terms
                'db_connection_label_sqlsrv' => 'sqlsrv', // Keep technical terms
                'db_host_label' => 'Hôte de la base de données', // Added 'de la base de données'
                'db_host_placeholder' => 'Hôte de la base de données', // Added 'de la base de données'
                'db_port_label' => 'Port de la base de données', // Added 'de la base de données'
                'db_port_placeholder' => 'Port de la base de données', // Added 'de la base de données'
                'db_name_label' => 'Nom de la base de données', // Added 'de la base de données'
                'db_name_placeholder' => 'Nom de la base de données', // Added 'de la base de données'
                'db_username_label' => 'Nom d\'utilisateur de la base de données', // Added 'de la base de données'
                'db_username_placeholder' => 'Nom d\'utilisateur de la base de données', // Added 'de la base de données'
                'db_password_label' => 'Mot de passe de la base de données', // Added 'de la base de données'
                'db_password_placeholder' => 'Mot de passe de la base de données', // Added 'de la base de données'

                'app_tabs' => [
                    'more_info' => 'Plus d\'informations',
                    'broadcasting_title' => 'Diffusion, Cache, Session & File d\'attente', // Translated terms, preserved &
                    'broadcasting_label' => 'Pilote de diffusion', // Translated "Driver"
                    'broadcasting_placeholder' => 'Pilote de diffusion',
                    'cache_label' => 'Pilote de cache', // Translated "Driver"
                    'cache_placeholder' => 'Pilote de cache',
                    'session_label' => 'Pilote de session', // Translated "Driver"
                    'session_placeholder' => 'Pilote de session',
                    'queue_label' => 'Pilote de file d\'attente', // Translated "Driver"
                    'queue_placeholder' => 'Pilote de file d\'attente',
                    'redis_label' => 'Pilote Redis', // Translated "Driver"
                    'redis_host' => 'Hôte Redis',
                    'redis_password' => 'Mot de passe Redis',
                    'redis_port' => 'Port Redis',

                    'mail_label' => 'Courriel', // Standard French for Mail
                    'mail_driver_label' => 'Pilote de courriel', // Translated "Driver"
                    'mail_driver_placeholder' => 'Pilote de courriel',
                    'mail_host_label' => 'Hôte de courriel',
                    'mail_host_placeholder' => 'Hôte de courriel',
                    'mail_port_label' => 'Port de courriel',
                    'mail_port_placeholder' => 'Port de courriel',
                    'mail_username_label' => 'Nom d\'utilisateur de courriel',
                    'mail_username_placeholder' => 'Nom d\'utilisateur de courriel',
                    'mail_password_label' => 'Mot de passe de courriel',
                    'mail_password_placeholder' => 'Mot de passe de courriel',
                    'mail_encryption_label' => 'Chiffrement de courriel',
                    'mail_encryption_placeholder' => 'Chiffrement de courriel',

                    'pusher_label' => 'Pusher', // Keep as is
                    'pusher_app_id_label' => 'ID App Pusher', // Reordered for better French flow
                    'pusher_app_id_palceholder' => 'ID App Pusher', // Corrected typo in key, reordered
                    'pusher_app_key_label' => 'Clé App Pusher', // Reordered for better French flow
                    'pusher_app_key_palceholder' => 'Clé App Pusher', // Corrected typo in key, reordered
                    'pusher_app_secret_label' => 'Secret App Pusher', // Reordered for better French flow
                    'pusher_app_secret_palceholder' => 'Secret App Pusher', // Corrected typo in key, reordered
                ],
                'buttons' => [
                    'setup_database' => 'Configurer la base de données',
                    'setup_application' => 'Configurer l\'application',
                    'install' => 'Installer',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Étape 3 | Paramètres d\'environnement | Éditeur Classique', // Corrected spelling
            'title' => 'Éditeur de texte classique',
            'save' => 'Enregistrer le fichier .env', // Added "le fichier"
            'back' => 'Utiliser l\'assistant de formulaire', // Added "l'"
            'install' => 'Enregistrer et installer',
        ],
        'success' => 'Vos paramètres de fichier .env ont été enregistrés.',
        'errors' => 'Impossible de sauvegarder le fichier .env, veuillez le créer manuellement.', // Rephrased slightly
    ],

    'install' => 'Installer',

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Terminé',
        'templateTitle' => 'Installation Terminée', // Added accent
        'finished' => 'L’application a été installée avec succès.', // Added accent
        'migration' => 'Sortie console Migration & Seed :', // Translated terms, preserved &, added colon and spacing
        'console' => 'Sortie console de l\'application :', // Added 'de l\'application', colon and spacing
        'log' => 'Entrée du journal d\'installation :', // Translated, added colon and spacing
        'env' => 'Fichier .env final :', // Added "Fichier", colon and spacing
        'exit' => 'Cliquez ici pour quitter',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater' => [
        /*
         *
         * Shared translations.
         *
         */
        'title' => 'Mise à jour de Laravel', // Translated "Updater"

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Bienvenue dans le programme de mise à jour...', // Slightly more formal than "updateur"
            'message' => 'Bienvenue dans l\'assistant de mise à jour.', // Translated "wizard"
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Aperçu',
            'message' => 'Il y a 1 mise à jour.|Il y a :number mises à jour.', // Handled pluralization and placeholder
            'install_updates' => 'Installer la mise à jour', // Singular seems appropriate for the button
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Terminé',
            'finished' => 'L’application a été mise à jour avec succès.', // Added accent, consistent phrasing
            'exit' => 'Cliquez ici pour quitter', // Consistent phrasing
        ],

        'log' => [
            'success_message' => 'L\'installateur Laravel a été mis à jour avec succès le ', // Added space after 'le'
        ],
    ],
];
