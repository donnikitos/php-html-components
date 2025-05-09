# Intuitive & reusable HTML components in PHP

A React-style component system for PHP – write reusable, class-based UI components with props, children, and safe rendering.

### Features

-   Components as PHP classes
-   Props and children
-   Escaped-by-default output
-   Self-closing and paired syntax
-   No templating engine – 100% native PHP

## Installation

```sh
composer require nititech/html-components
```

---

## Usage

### Creating components

Write components directly using native PHP syntax — similar to how JSX mixes HTML and logic:

```php
<?php

namespace components;

class Message extends \HTML\Component {
    public function render() {
        $style = match ($this->variant) {
            'success' => 'background-color: #e6ffed; color: #2f855a; border: 1px solid #c6f6d5;',
            'error'   => 'background-color: #ffe6e6; color: #c53030; border: 1px solid #feb2b2;',
            'info'    => 'background-color: #ebf8ff; color: #2b6cb0; border: 1px solid #bee3f8;',
            default   => 'background-color: #f7fafc; color: #2d3748; border: 1px solid #e2e8f0;',
        };
?>
    <div style="padding: 1rem; margin-bottom: 1rem; border-radius: 6px; <?= $style ?>">
        <div style="font-weight: bold; margin-bottom: 0.5rem;">
            <?= ucfirst($this->variant ?? 'Note') ?> 🔔
        </div>
        <div>
            <?= $this->children; ?>
        </div>
    </div>
<?php
    }
}
```

### Rendering components

```php
<?php $msg = new \components\Message(['variant' => 'success']); ?>
    Your profile was updated successfully.<br />
    <a href="/cool">Continue</a>
<?php $msg->close(); ?>
```

---

## Alternative usage

### Self-Closing Component

```php
<?php \components\Example::closed(['foo' => 'bar', 'class' => 'test']); ?>
```

### With JSON Props

```php
<?php \components\Example::closed('{"foo":"bar","class":"test"}'); ?>
```

---

## Output Modes

Components can either echo directly or return a string for further processing (e.g. passing into templates or APIs).

### With closing tag

```php
<?php $msg = new \components\Message(['variant' => 'success']); ?>
    Your profile was updated successfully.<br />
    <a href="/cool">Continue</a>
<?php
$html = $msg->close(true); // Returns HTML string instead of echoing
?>
```

### Self-closing

```php
$html = \components\Message::closed(
  ['variant' => 'success', 'children' => 'Something went wrong.'],
  true, // Set last parameter to return as HTML string
);
```

---

## Notes

-   HTML is written inline using regular PHP – no templating language required
-   `<?= $this->children; ?>` is unescaped inner content, other properties are escaped and can be safely used in the HTML context
-   You can mix control logic, conditions, and loops directly in PHP

#### Props & Escaping

| Access                    | Escaped? | Example                             |
| ------------------------- | -------- | ----------------------------------- |
| `$this->foo`              | ✅ Yes   | Safe for direct HTML injection      |
| `$this->__props__['foo']` | ❌ No    | Use for raw values (e.g. JSON, IDs) |
| `$this->children`         | ❌ No    | Direct inner content (slot-like)    |

> **Note:** Only children is unescaped by default. All other props accessed as $this->prop_name are HTML-escaped for safety.

---

## Issues

If you encounter any other bugs or need some other features feel free to open an [issue](https://github.com/donnikitos/php-html-components/issues).

---

## Support

Love open source? Enjoying my project?\
Your support can keep the momentum going! Consider a donation to fuel the creation of more innovative open source software.

<table>
    <tr>
        <td>
            via Ko-Fi
        </td>
        <td>
            Buy me a coffee
        </td>
        <td>
            via PayPal
        </td>
    </tr>
    <tr>
        <td>
            <a href="https://ko-fi.com/Y8Y2ALMG" target="_blank"><img src="https://ko-fi.com/img/githubbutton_sm.svg" alt="Ko-Fi" width="174"></a>
        </td>
        <td>
            <a href="https://www.buymeacoffee.com/donnikitos" target="_blank"><img src="https://nititech.de/donate-buymeacoffee.png" alt="Buy Me A Coffee" width="174"></a>
        </td>
        <td>
            <a href="https://www.paypal.com/donate/?hosted_button_id=EPXZPRTR7JHDW" target="_blank"><img src="https://nititech.de/donate-paypal.png" alt="PayPal" width="174"></a>
        </td>
    </tr>
</table>
