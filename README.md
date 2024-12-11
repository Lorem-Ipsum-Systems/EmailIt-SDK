# EmailItMailer Library

The `EmailItMailer` library is a PHP wrapper for interacting with the **EmailIt API**, allowing you to send emails and manage sending domains effortlessly. Built on top of `GuzzleHttp`, this library aims to streamline the process of integrating email features into your project.

---

## Features

- Send emails with HTML, text, custom headers, and file attachments.
- Retrieve a list of sending domains with pagination and filtering.
- Fetch detailed information about a specific sending domain.

---

## Requirements

- PHP 8.1 or higher
- Composer
- Required dependencies (managed via Composer):
    - `guzzlehttp/guzzle` (HTTP client)
    - `psr/http-client`, `psr/http-message`, `psr/http-factory`
    - `ralouphie/getallheaders`
    - `symfony/deprecation-contracts`

---

## Installation

Use Composer to install the library:

```bash
composer require lorem-ipsum/email-it
```

---

## Usage

### Initialization

First, create a new instance of `EmailItMailer` by providing your API key.

```php
use LoremIpsum\EmailIt\EmailItMailer;

$apiKey = 'your-api-key';
$emailIt = new EmailItMailer($apiKey);
```

### Sending an Email

To send an email, use the `send` method. This method supports the following parameters:

- `from`: Sender's email address.
- `to`: Recipient's email address.
- `subject`: Email subject.
- `htmlContent`: (Optional) HTML email content.
- `textContent`: (Optional) Plain text email content.
- `attachments`: (Optional) Array of file attachments.
- `headers`: (Optional) Array of custom headers.

Here’s an example:

```php
try {
    $response = $emailIt->send(
        'sender@example.com', // Sender
        'recipient@example.com', // Recipient
        'Hello, World!', // Subject
        '<p>This is a test email</p>', // HTML Content
        'This is a test email', // Text Content
        [], // Attachments (optional)
        ['X-Custom-Header' => 'value'] // Custom Headers (optional)
    );
    print_r($response);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

### Managing Sending Domains

#### Get a List of Sending Domains

Retrieve a paginated list of all sending domains using the `getSendingDomains` method:

```php
try {
    $domains = $emailIt->getSendingDomains(1, 10, 'example'); // Page 1, 10 results, search term "example"
    print_r($domains);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

#### Fetch Details of a Sending Domain

To get detailed information about a specific sending domain, use the `getSendingDomain` method:

```php
try {
    $domainDetails = $emailIt->getSendingDomain('domain-id');
    print_r($domainDetails);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

## Error Handling

All methods in the `EmailItMailer` class throw standard PHP `Exception`s. Common reasons for exceptions include:

- Invalid API credentials.
- HTTP or network errors during API requests.
- Invalid input, such as malformed attachments.

Use `try...catch` blocks to handle exceptions gracefully, as shown in the examples above.

---

## Contributing

We welcome contributions to improve the library. Feel free to:

1. Fork the repository.
2. Submit a Pull Request (PR) with your improvements.
3. Ensure all code changes include tests and documentation updates (if applicable).

---

## License

This library is released under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## Support

If you encounter any issues or need further assistance, please reach out at support@example.com or open a GitHub issue.

---

## Acknowledgments

This library leverages the following dependencies:

- [GuzzleHttp](https://github.com/guzzle/guzzle) for making HTTP requests.
- PSR request and response libraries for standardized HTTP message handling.

---

### Notes for Developers

- Ensure you have proper API documentation access while working with the API endpoints.
- Always validate attachments before sending email requests to avoid errors.

---