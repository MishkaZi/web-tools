# Web Development Tools Platform

A dynamic web platform for managing and hosting various web development tools. Built with PHP 8.2+, featuring a modern MVC architecture and real-time tool management capabilities.

## 🚀 Features

- **Dynamic Tool Management**
  - Add new tools via drag-and-drop interface
  - Delete tools with confirmation
  - Automatic tool integration into the platform
  - Support for multiple file uploads

- **Modern Architecture**
  - MVC pattern implementation
  - Clean routing system
  - Template engine
  - Modular design

- **User Interface**
  - Responsive design with Tailwind CSS
  - Modal system for interactions
  - Real-time feedback
  - Mobile-friendly layout

- **Developer Experience**
  - Debug mode with detailed logging
  - Clear error handling
  - Easy tool deployment
  - Extensible architecture

## 🛠 Tech Stack

- PHP 8.2+
- JavaScript (ES6+)
- Tailwind CSS
- Dropzone.js
- Custom MVC Framework

## 📋 Requirements

- PHP >= 8.2
- Web Server (Apache/Nginx)
- mod_rewrite enabled
- FileInfo PHP Extension
- JSON PHP Extension

## 🔧 Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/web-tools.git
cd web-tools
```

2. Install dependencies:
```bash
composer install
```

3. Configure your web server:
   - Point document root to the `public` directory
   - Ensure mod_rewrite is enabled
   - Set proper permissions for tools directory

4. Configure the application:
   - Copy `.htaccess.example` to `.htaccess`
   - Adjust RewriteBase in .htaccess if needed
   - Set proper permissions for the tools directory:
```bash
chmod 755 tools
```

## 📁 Project Structure

```
/project-root
│── public/
│   │── assets/
│   │   │── css/
│   │   │── js/
│   │   │── images/
│   │── .htaccess
│   │── index.php
│
│── src/
│   │── Core/
│   │   ├── Config.php
│   │   ├── Router.php
│   │   ├── Template.php
│   │   ├── Helper.php
│   │── views/
│   │   │── layouts/
│   │   │── errors/
│   │── tools/
│
│── vendor/
│── composer.json
```

## 🚀 Adding New Tools

1. Click "Add New Tool" button on the homepage
2. Enter tool name
3. Upload tool files via drag-and-drop
4. Tool is automatically integrated and available

## 🔒 Security

- File upload restrictions
- Input sanitization
- Path traversal prevention
- XSS protection
- CSRF protection (upcoming)

## 🔄 API Endpoints

- `POST /api/tool-upload` - Upload new tool
- `POST /api/tool-delete` - Delete existing tool

## 👥 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🙏 Acknowledgments

- [Tailwind CSS](https://tailwindcss.com/)
- [Dropzone.js](https://www.dropzonejs.com/)
- [PHP Framework Community](https://php-fig.org/)

## 📞 Contact

Your Name - [@yourusername](https://twitter.com/yourusername)
Project Link: [https://github.com/yourusername/web-tools](https://github.com/yourusername/web-tools)