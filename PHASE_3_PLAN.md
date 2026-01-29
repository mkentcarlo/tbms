# Phase 3: Page Migration Plan

## Overview
Migrate existing pages from CoreUI/Bootstrap to the new modern Tailwind UI components.

## Migration Strategy

### Priority Order

#### High Priority (Week 3)
1. **Authentication Pages**
   - Login page
   - Register page (if exists)
   - Password reset pages

2. **Dashboard Homepage**
   - Main dashboard/index page

3. **User Management Pages**
   - User list/index
   - User create form
   - User edit form
   - User show/view

#### Medium Priority (Week 4)
4. **Expense Management**
   - Expense list/index
   - Expense create form
   - Expense edit form
   - Expense print/view

5. **Office Management**
   - Office list/index
   - Office create/edit forms
   - Office categories

6. **Reports Pages**
   - Reports index
   - Reports export

#### Lower Priority (Weeks 5-6)
7. **Settings & Admin Pages**
   - Roles management
   - Menu builder
   - Media library
   - Other admin pages

## Migration Process for Each Page

1. Create new Blade template using `@extends('layouts.modern')`
2. Convert HTML/CSS classes to Tailwind utility classes
3. Replace old components with new ones:
   - Tables → `<x-table>` component
   - Forms → New form components
   - Buttons → Button classes (.btn-primary, etc.)
   - Cards → Card classes (.card, .card-header, etc.)
4. Replace jQuery with Alpine.js where needed
5. Test functionality
6. Test responsiveness
7. Deploy and verify

## Migration Checklist

For each page:
- [ ] Layout changed to `@extends('layouts.modern')`
- [ ] All Bootstrap classes replaced with Tailwind
- [ ] Old components replaced with new ones
- [ ] Forms updated to use new form components
- [ ] Tables updated to use new table component
- [ ] Buttons updated to use new button classes
- [ ] JavaScript/jQuery replaced with Alpine.js
- [ ] Responsive design verified
- [ ] Functionality tested
- [ ] No broken links or routes

## Notes

- Keep old pages available during migration for comparison
- Migrate one page at a time
- Test thoroughly before moving to next page
- Document any issues or breaking changes
