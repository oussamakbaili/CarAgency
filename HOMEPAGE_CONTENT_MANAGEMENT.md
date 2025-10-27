# Homepage Content Management System

## Overview
This document describes the new Homepage Content Management system that allows admins to control which cars and agencies appear on the public homepage.

## Features Implemented

### 1. Database Fields Added
Four new fields have been added to both `cars` and `agencies` tables:
- **`featured`** (boolean): Marks items as featured/highlighted
- **`show_on_homepage`** (boolean): Controls visibility on the homepage
- **`homepage_priority`** (integer 0-100): Determines display order (higher = higher priority)
- **`featured_at`** (timestamp): Tracks when an item was marked as featured

### 2. Data Flow & Filtering

#### Homepage Display Logic
The public homepage now uses an intelligent filtering system:

1. **Only shows items marked for homepage display**:
   - Cars with `show_on_homepage = true`
   - From agencies with `show_on_homepage = true` and `status = approved`
   - Only available cars (`status = available`)

2. **Prioritization Order**:
   - Featured items appear first
   - Then sorted by priority value (0-100)
   - Finally by average rating
   - Formula: `(featured ? 10000 : 0) + (priority × 100) + rating`

3. **Sections**:
   - **Top Picks**: Shows first 4 cars based on priority
   - **Discover**: Shows up to 12 cars based on priority

#### Category & Location Filtering
- Filtering by category and location still works
- Filtered results respect the homepage visibility and priority settings
- Real-time data updates as admin makes changes

### 3. Admin Control Panel

#### Accessing the Feature
- Navigate to **Admin Dashboard** → **Système** → **Contenu Page d'accueil**
- Or visit: `/admin/featured`

#### Interface Sections

##### Cars Management Tab
- **Featured Cars Section**: Shows all currently featured cars
- **All Homepage Cars Table**: Lists all cars with homepage controls

##### Agencies Management Tab  
- **Featured Agencies Section**: Shows all currently featured agencies
- **All Homepage Agencies Table**: Lists all agencies with homepage controls

#### Admin Actions Available

##### Per-Item Actions
1. **Toggle Featured**:
   - Mark/unmark cars or agencies as featured
   - Featured items get a star badge and priority boost

2. **Toggle Homepage Visibility**:
   - Show/hide items from homepage
   - Hidden items won't appear even if they have high priority

3. **Set Priority** (0-100):
   - Control display order within the homepage
   - Higher numbers appear first
   - Update in real-time by changing the number

##### Bulk Actions
1. **Mark as Featured**: Apply featured status to multiple items
2. **Remove Featured**: Remove featured status from multiple items
3. **Show on Homepage**: Make multiple items visible on homepage
4. **Hide from Homepage**: Hide multiple items from homepage

### 4. API Endpoints

#### Cars
- `POST /admin/featured/cars/{car}/toggle-featured` - Toggle featured status
- `POST /admin/featured/cars/{car}/toggle-homepage` - Toggle homepage visibility
- `PUT /admin/featured/cars/{car}/priority` - Update priority (requires: `priority` 0-100)
- `POST /admin/featured/cars/bulk-update` - Bulk update cars

#### Agencies
- `POST /admin/featured/agencies/{agency}/toggle-featured` - Toggle featured status
- `POST /admin/featured/agencies/{agency}/toggle-homepage` - Toggle homepage visibility
- `PUT /admin/featured/agencies/{agency}/priority` - Update priority (requires: `priority` 0-100)
- `POST /admin/featured/agencies/bulk-update` - Bulk update agencies

### 5. Model Scopes Added

#### Car Model
```php
Car::featured()              // Get only featured cars
Car::showOnHomepage()         // Get cars marked for homepage
Car::orderByPriority()        // Order by priority and date
```

#### Agency Model
```php
Agency::featured()            // Get only featured agencies
Agency::showOnHomepage()      // Get agencies marked for homepage
Agency::orderByPriority()     // Order by priority and date
```

## Usage Guide for Administrators

### Initial Setup
By default, all existing cars and agencies have:
- `show_on_homepage = true` (visible)
- `featured = false` (not featured)
- `homepage_priority = 0` (no priority boost)

### Recommended Workflow

1. **Identify Top Performers**:
   - Look at cars/agencies with best ratings
   - Consider customer favorites
   - Check recent popular rentals

2. **Set Homepage Visibility**:
   - Hide underperforming or problematic cars
   - Hide suspended agencies
   - Show only quality items

3. **Mark Featured Items** (4-6 items recommended):
   - Select best cars for "Top Picks" section
   - Feature top-rated agencies
   - Rotate featured items monthly

4. **Set Priorities**:
   - Priority 90-100: Premium/newest cars
   - Priority 70-89: Popular cars
   - Priority 50-69: Good cars
   - Priority 0-49: Standard cars

5. **Monitor & Adjust**:
   - Check homepage regularly
   - Rotate featured items
   - Update priorities based on performance

### Best Practices

1. **Feature Rotation**: Change featured items every 2-4 weeks
2. **Balance**: Don't feature too many items (dilutes impact)
3. **Quality First**: Only feature items with good ratings
4. **Test Filters**: Ensure category/location filters still work
5. **Mobile View**: Check how featured items appear on mobile

### Troubleshooting

**Q: Car isn't showing on homepage?**
- Check `show_on_homepage` is enabled
- Verify agency is approved and visible on homepage
- Ensure car status is "available"

**Q: Featured car not appearing first?**
- Check priority value (should be high)
- Verify it's in the Top Picks section
- Other items might have higher combined score

**Q: Changes not reflecting immediately?**
- Refresh the public homepage
- Clear browser cache
- Check if filters are applied

## Technical Details

### Database Migration
Location: `database/migrations/2025_10_12_000001_add_featured_and_homepage_fields_to_cars_and_agencies_tables.php`

### Controller
Location: `app/Http/Controllers/Admin/FeaturedContentController.php`

### View
Location: `resources/views/admin/featured/index.blade.php`

### Routes
Location: `routes/web.php` (Admin middleware group → featured prefix)

## Future Enhancements
Possible improvements for future versions:
- Scheduled featuring (auto feature/unfeature on dates)
- A/B testing of featured items
- Analytics on featured item performance
- Automated recommendation for featuring
- Mobile app integration
- Multi-language support for featured descriptions

## Support
For technical support or questions, contact the development team.

---
Last Updated: October 12, 2025

