# Events Management Module

## Description

The Events Management module is a custom Drupal module designed to manage events on your Drupal site. It provides functionality to list, add, edit, and delete events. Each event includes details such as title, image, description, start time, end time, and category.

## Features

- List all events with details.
- Add new events.
- Edit existing events.
- Delete events.
- Display operations for each event (Edit, Delete).

## Installation

Follow these steps to install and enable the Events Management module:

1. **Download the module:**
   - Place the `events_management` module in the `web/modules/custom` directory of your Drupal installation.

2. **Enable the module:**
   - Navigate to the Extend page (`/admin/modules`) in your Drupal site.
   - Find the "Events Management" module in the list and check the box next to it.
   - Click the "Install" button at the bottom of the page.

3. **Run database updates:**
   - Run the database updates to create the necessary tables for the module. You can do this by navigating to `/update.php` in your browser or by running the following Drush command:
     ```sh
     drush updb
     ```

4. **Clear the cache:**
   - Clear the Drupal cache to ensure that the module is fully loaded. You can do this by navigating to `/admin/config/development/performance` and clicking the "Clear all caches" button, or by running the following Drush command:
     ```sh
     drush cr
     ```

## Usage

1. **List Events:**
   - Navigate to `/events` to see the list of all events.

2. **Add Event:**
   - Click on the "Add Event" link at the top of the events list page to add a new event.

3. **Edit Event:**
   - Click on the "Edit" link next to an event to edit its details.

4. **Delete Event:**
   - Click on the "Delete" link next to an event to delete it.

## Customization

- The module uses a custom library for styling and functionality. Ensure that the `events_management` library is defined in your module's `events_management.libraries.yml` file and that the necessary CSS and JS files are included.

## Support

For any issues or questions, please contact the module maintainer.
