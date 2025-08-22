# PHP MVC CMS Framework

A powerful, WordPress-like content management system built with PHP using the MVC (Model-View-Controller) architectural pattern.

## Features

### Core Features

- **MVC Architecture**: Clean separation of concerns with dedicated Models, Views, and Controllers
- **User Management**: Complete authentication system with role-based access control (Admin, Editor, Author, Subscriber)
- **Content Management**: Create, edit, and manage posts and pages with WYSIWYG editor
- **Media Library**: Upload and manage images and files with metadata support
- **Categories & Tags**: Organize content with hierarchical categories and flexible tagging
- **SEO Optimization**: Built-in meta title, description, and sitemap generation
- **Theme System**: Modular theme architecture for easy customization
- **Plugin Architecture**: Extensible plugin system for added functionality

### Admin Features

- **Modern Dashboard**: Clean, responsive admin interface with statistics and quick actions
- **Rich Text Editor**: TinyMCE integration for content creation
- **Media Management**: Drag-and-drop file uploads with image optimization
- **User Roles**: Granular permission system for different user types
- **Settings Panel**: Configurable site options and preferences

### Frontend Features

- **Responsive Design**: Mobile-first, Bootstrap-powered frontend
- **SEO-Friendly URLs**: Clean, search engine optimized URL structure
- **Search Functionality**: Built-in content search with relevant results
- **Comment System**: User engagement with threaded comments
- **Social Sharing**: Easy content sharing capabilities

### Technical Features

- **Database Abstraction**: Secure PDO-based database layer with prepared statements
- **Security**: CSRF protection, input validation, and XSS prevention
- **Caching**: Performance optimization with smart caching mechanisms
- **Extensible**: Easy to extend with custom functionality

## Installation

1. **Clone or download** the project files to your web server
2. **Configure your web server** to point to the `public` directory
3. **Create a MySQL database** for the CMS
4. **Import the database schema** from `database/schema.sql`
5. **Configure database settings** in `config/config.php`
6. **Set proper permissions** on the `storage` directory for uploads

## Configuration

### Database Setup

```php
// config/config.php
$config['database'] = [
    'host' => 'localhost',
    'database' => 'your_cms_db',
    'username' => 'your_username',
    'password' => 'your_password',
];
```

### Environment Variables

Create a `.env` file in the root directory:

```dotenv
DB_HOST=localhost
DB_DATABASE=cms_db
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost
DEBUG=true
```

## Usage

### Admin Access

- Navigate to `/admin` to access the admin dashboard
- Default credentials: `admin@example.com` / `password`
- Change these credentials immediately after installation

### Creating Content

1. **Posts**: Go to Admin → Posts → Add New
2. **Pages**: Go to Admin → Pages → Add New
3. **Categories**: Organize content under Admin → Categories
4. **Media**: Upload files through Admin → Media

### Theme Development

Themes are located in the `themes` directory. Each theme should include:

- `index.php` - Homepage template
- `single.php` - Single post template
- `page.php` - Page template
- `category.php` - Category archive template
- `search.php` - Search results template
- `404.php` - Not found template

### Plugin Development

Plugins are stored in the `plugins` directory. Each plugin should:

- Have its own directory
- Include a main PHP file with the same name as the directory
- Follow the plugin API for hooks and filters

## File Structure

```plaintext
/
├── app/
│   ├── controllers/     # Application controllers
│   ├── models/         # Data models
│   ├── views/          # View templates
│   └── helpers/        # Helper classes
├── config/             # Configuration files
├── database/           # Database migrations and schema
├── plugins/            # Plugin directory
├── public/             # Web accessible files
├── storage/            # File uploads and cache
├── system/             # Core framework files
├── themes/             # Theme templates
└── README.md
```

## Security Features

- **SQL Injection Protection**: Prepared statements and parameterized queries
- **XSS Prevention**: Output escaping and input validation
- **CSRF Protection**: Token-based request validation
- **Authentication**: Secure password hashing and session management
- **File Upload Security**: Type validation and secure storage
- **Security Headers**: XSS protection, content type options, and frame options

## Performance

- **Database Optimization**: Indexed queries and efficient data retrieval
- **Static File Caching**: Browser caching for CSS, JS, and images
- **Gzip Compression**: Reduced bandwidth usage
- **Optimized Code**: Minimal resource usage and fast response times

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache with mod_rewrite or Nginx
- **Extensions**: PDO, GD, mbstring, openssl

## Contributing

This CMS framework is built for educational and development purposes. Feel free to:

- Report bugs and issues
- Suggest new features
- Submit improvements
- Create plugins and themes

## License

This project is open source and available under the MIT License.
