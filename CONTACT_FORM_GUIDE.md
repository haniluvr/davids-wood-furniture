# Contact Form Integration Guide

## Overview
The footer contact form is now fully integrated with the database for customer support and contact management.

## Features Implemented

### 1. **Database Storage**
- All contact form submissions are stored in the `contact_messages` table
- Tracks: name, email, message, status, timestamps
- Links to user account if submitted by logged-in user
- Supports guest submissions

### 2. **Frontend Form**
- Location: Footer section on all pages
- Fields: Name, Email, Message
- Auto-fills name and email for logged-in users
- AJAX submission with loading states
- Success/error feedback messages
- Form validation

### 3. **Admin Management Panel**
Access: `https://admin.davidswood.test/contact-messages`

**Features:**
- View all contact messages
- Filter by status (New, Read, Responded, Archived)
- New message counter badge in sidebar
- Mark messages as read automatically when viewed
- Add admin notes to messages
- Update message status
- Delete messages
- Quick "Reply via Email" link

**Status Options:**
- `new` - Newly submitted (default)
- `read` - Admin has viewed the message
- `responded` - Admin has responded to the customer
- `archived` - Message has been archived

### 4. **API Endpoint**
- **Route:** `POST /contact`
- **Method:** POST
- **Fields:**
  - `name` (required, string, max 255)
  - `email` (required, email, max 255)
  - `message` (required, string, max 5000)
- **Response:** JSON
  ```json
  {
    "success": true,
    "message": "Thank you for your message! We'll respond within 1-2 business days."
  }
  ```

## Database Schema

```sql
Table: contact_messages
- id: bigint unsigned primary key
- user_id: bigint unsigned nullable (foreign key to users)
- name: varchar(255)
- email: varchar(255)
- message: text
- status: enum('new', 'read', 'responded', 'archived') default 'new'
- admin_notes: text nullable
- read_at: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
```

## Admin Navigation

The Contact Messages section appears in the admin sidebar:
- **Menu:** Contact Messages (with new messages badge)
- **Submenu:**
  - All Messages
  - New Messages
  - Read
  - Responded

## Usage Examples

### For Customers
1. Scroll to footer on any page
2. Fill out the contact form
3. Click "Send message"
4. Receive confirmation message
5. Expect response within 1-2 business days

### For Admins
1. Log in to admin panel
2. Navigate to "Contact Messages" in sidebar
3. See new message count badge
4. Click on any message to view details
5. Add admin notes for internal tracking
6. Update status as you work on the message
7. Click "Reply via Email" to respond to customer

## Model Relationships

```php
ContactMessage::class
├── belongsTo(User::class) // Optional - if user was logged in
└── Scopes:
    ├── new() // Get new messages only
    └── unread() // Get unread messages

User::class
└── hasMany(ContactMessage::class) // All messages from this user
```

## Logging

All submissions are logged in `storage/logs/laravel.log`:
- Successful submissions: `Contact form submitted`
- Failed submissions: `Contact form submission failed`

## Testing

1. **Test as Guest:**
   ```
   Visit homepage → Scroll to footer → Fill contact form → Submit
   ```

2. **Test as Logged-in User:**
   ```
   Login → Navigate to any page → Scroll to footer → Notice auto-filled fields → Submit
   ```

3. **Test Admin Panel:**
   ```
   Access admin.davidswood.test → Login → Click Contact Messages → View, filter, update messages
   ```

## Future Enhancements (Optional)

- Email notifications to admin when new message arrives
- Email notifications to customer when admin responds
- Attach files to contact form
- Rich text editor for admin notes
- Export contact messages to CSV
- Search functionality in admin panel
- Customer contact history in user profile
- Auto-categorization of messages (inquiry, complaint, feedback, etc.)

## Files Modified/Created

### Created Files:
- `app/Models/ContactMessage.php`
- `app/Http/Controllers/ContactController.php`
- `database/migrations/2025_10_13_175359_create_contact_messages_table.php`
- `resources/views/admin/contact-messages/index.blade.php`
- `resources/views/admin/contact-messages/show.blade.php`

### Modified Files:
- `routes/web.php` (added contact form routes and admin routes)
- `resources/views/partials/footer.blade.php` (added form fields, CSRF token, and JavaScript)
- `resources/views/admin/partials/sidebar.blade.php` (added Contact Messages menu)

## Support

For questions or issues related to the contact form system, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Browser console for JavaScript errors
3. Database for stored messages: `contact_messages` table
4. Admin panel for message management

---

**Last Updated:** October 13, 2025
**Version:** 1.0

