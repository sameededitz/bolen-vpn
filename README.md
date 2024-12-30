# Bolen VPN Admin Panel

The **Bolen VPN Admin Panel** is a robust and user-friendly dashboard designed to efficiently manage VPN servers, users, and subscriptions. With advanced features like server and user management, Bolen VPN ensures secure and seamless connectivity for its users.

---

## Features

### 🌐 **Server Management**
- **Add Servers**: Easily add new VPN servers to the network.
- **Edit Server Details**: Update server configurations, locations, or status.
- **Monitor Servers**: View server status and connected users in real-time.

### 👥 **User Management**
- **Add Users**: Manually create user accounts.
- **Edit Users**: Modify user details such as username, email, or subscription status.
- **Deactivate/Activate Users**: Quickly manage user access permissions.

### 💳 **Subscription & Plan Management**
- **Manage Plans**: Create, update, and delete VPN subscription plans.
- **User Subscriptions**: Assign or revoke subscriptions for users.
- **Purchase Tracking**: Monitor user purchase history and active plans.

### 📊 **Analytics & Logs**
- **User Logs**: Track user activity and connection history.
- **Server Usage Statistics**: Get detailed insights into server load and performance.

---

## Installation

### Prerequisites
- PHP 8.2+
- Laravel 11
- MySQL
- Composer

### Steps
1. **Clone the repository**:
    ```bash
    git clone https://github.com/sameededitz/bolen-vpn.git
    ```
2. **Navigate to the project directory**:
    ```bash
    cd bolen-vpn
    ```
3. **Install dependencies**:
    ```bash
    composer install
    ```
4. **Set up environment variables**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. **Run migrations and seeders**:
    ```bash
    php artisan migrate --seed
    ```
6. **Start the server**:
    ```bash
    php artisan serve
    ```

---

## Usage

### Admin Features
- **Dashboard**: Get an overview of servers, users, and subscriptions.
- **Server Management**: Add or modify servers and monitor server activity.
- **User Management**: Handle user accounts and subscription plans.
- **Plan Management**: Configure subscription plans and pricing.

---

## Technologies Used
- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Bootstrap
- **Database**: MySQL

---

## Developer Information
- **Developer**: Sameed
- **Instagram**: [@not_sameed52](https://www.instagram.com/not_sameed52/)
- **Discord**: sameededitz
- **Linktree**: [linktr.ee/sameededitz](https://linktr.ee/sameededitz)
- **Company**: TecClubb
  - **Website**: [https://tecclubb.com/](https://tecclubb.com/)
  - **Contact**: tecclubb@gmail.com

---

## Contributing
Contributions are welcome! Fork the repository, create a new branch, and submit a pull request. Open an issue first for significant changes.

---

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Contact
For inquiries or support, reach out via:
- **Email**: tecclubb@gmail.com
- **Website**: [https://tecclubb.com/](https://tecclubb.com/)
