-- MySQL Import Script for siterians_clubhive_v2
-- This script will drop and recreate the database to ensure clean import

-- Drop the database if it exists
DROP DATABASE IF EXISTS siterians_clubhive_v2;

-- Create the database with proper charset and collation
CREATE DATABASE siterians_clubhive_v2 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE siterians_clubhive_v2;

-- Now you can import your fixed SQL file
-- Run this command after executing this script:
-- mysql -u root -p siterians_clubhive_v2 < siterians_clubhive_v2.sql
