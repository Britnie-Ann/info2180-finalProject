-- combined_schema.sql

-- Create the Dolphin CRM database while also making sure it doesn't already exist
CREATE DATABASE IF NOT EXISTS dolphin_crm;

-- Use the Dolphin CRM database
USE dolphin_crm;

-- Drop existing tables if they exist to avoid conflicts
DROP TABLE IF EXISTS notes;
DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS users;

-- Create Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Unique identifier for each user
    first_name VARCHAR(50) NOT NULL,            -- User's first name (mandatory)
    last_name VARCHAR(50) NOT NULL,             -- User's last name (mandatory)
    password VARCHAR(255) NOT NULL,              -- User's password (hashed)
    email VARCHAR(100) NOT NULL UNIQUE,          -- User's email (must be unique)
    role VARCHAR(50),                            -- Role of the user (e.g., admin, user)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp for when the user was created
);

-- Create Contacts Table
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Unique identifier for each contact
    title VARCHAR(50),                           -- Title of the contact (e.g., Mr., Ms.)
    first_name VARCHAR(50) NOT NULL,            -- Contact's first name (mandatory)
    last_name VARCHAR(50) NOT NULL,             -- Contact's last name (mandatory)
    email VARCHAR(100),                          -- Contact's email
    telephone VARCHAR(15),                       -- Contact's telephone number
    company VARCHAR(100),                        -- Company name associated with the contact
    type VARCHAR(50),                            -- Type of contact (e.g., client, lead)
    assigned_to INT,                             -- User ID of the user the contact is assigned to
    created_by INT,                             -- User ID of the user who created the contact
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp for when the contact was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Timestamp for last update
    FOREIGN KEY (assigned_to) REFERENCES users(id), -- Foreign key constraint referencing users table
    FOREIGN KEY (created_by) REFERENCES users(id) -- Foreign key constraint referencing users table
);

-- Create Notes Table
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Unique identifier for each note
    contact_id INT,                              -- ID of the contact associated with the note
    comment TEXT,                                -- Content of the note
    created_by INT,                              -- User ID of the user who created the note
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp for when the note was created
    FOREIGN KEY (contact_id) REFERENCES contacts(id), -- Foreign key constraint referencing contacts table
    FOREIGN KEY (created_by) REFERENCES users(id) -- Foreign key constraint referencing users table
);
