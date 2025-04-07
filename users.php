<?php

// Define the path to the users file
define('USERS_FILE', 'data/users.txt');

// Ensure data directory exists
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

/**
 * Get all users from the file
 * @return array Array of user data
 */
function getUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    
    $content = file_get_contents(USERS_FILE);
    if (empty($content)) {
        return [];
    }
    
    return unserialize($content);
}

/**
 * Save all users to the file
 * @param array $users Array of user data
 * @return bool Success or failure
 */
function saveUsers($users) {
    // Check if data directory exists and is writable
    if (!is_dir('data')) {
        if (!mkdir('data', 0777, true)) {
            error_log('Failed to create data directory');
            return false;
        }
    }
    
    // Make sure directory is writable
    if (!is_writable('data')) {
        chmod('data', 0777);
        error_log('Changed permissions on data directory');
    }
    
    // Try to save the file
    $result = file_put_contents(USERS_FILE, serialize($users));
    if ($result === false) {
        error_log('Failed to write to ' . USERS_FILE);
        return false;
    }
    
    return true;
}

/**
 * Register a new user
 * @param string $username Username
 * @param string $password Password (will be hashed)
 * @param string $email User's email
 * @return bool|string Success or error message
 */
function registerUser($username, $password, $email) {
    // Get existing users
    $users = getUsers();
    
    // Check if username already exists
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return 'Username already exists';
        }
        if ($user['email'] === $email) {
            return 'Email already in use';
        }
    }
    
    // Create new user with hashed password
    $users[] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'email' => $email,
        'created' => date('Y-m-d H:i:s'),
        'highscore' => 0
    ];
    
    // Save users
    if (saveUsers($users)) {
        return true;
    }
    
    return 'Failed to save user data';
}

/**
 * Authenticate a user
 * @param string $username Username
 * @param string $password Password
 * @return bool|array False if authentication fails, user data if successful
 */
function loginUser($username, $password) {
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

/**
 * Update user's high score
 * @param string $username Username
 * @param int $score New score
 * @return bool Success or failure
 */
function updateHighScore($username, $score) {
    $users = getUsers();
    
    foreach ($users as &$user) {
        if ($user['username'] === $username) {
            if ($score > ($user['highscore'] ?? 0)) {
                $user['highscore'] = $score;
                return saveUsers($users);
            }
            return true; // No update needed
        }
    }
    
    return false; // User not found
}

/**
 * Get top players by highscore
 * @param int $limit Number of top players to return
 * @return array List of top players
 */
function getTopPlayers($limit = 5) {
    $users = getUsers();
    
    // Sort by highscore (descending)
    usort($users, function($a, $b) {
        return ($b['highscore'] ?? 0) - ($a['highscore'] ?? 0);
    });
    
    // Return only the top N players
    return array_slice($users, 0, $limit);
}
?>