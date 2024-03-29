# View Helper - Cycle

## Introduction

The `Cycle` helper is used to alternate a set of values.

## Basic Usage

To add elements to cycle just specify them in constructor:

```php
<table>
    <?php foreach ($this->books as $book): ?>
        <tr style="background-color: <?php echo $this->cycle(array('#F0F0F0', '#FFF'))
                                                     ->next() ?>">
            <td><?php echo $this->escapeHtml($book['author']) ?></td>
        </tr>
    <?php endforeach ?>
</table>
```

The output:

```php
<table>
    <tr style="background-color: #F0F0F0">
       <td>First</td>
    </tr>
    <tr style="background-color: #FFF">
       <td>Second</td>
    </tr>
</table>
```

Or use `assign(array $data)` method and moving in backwards order:

```php
<?php $this->cycle()->assign(array('#F0F0F0', '#FFF')) ?>

<table>
    <?php foreach ($this->books as $book): ?>
    <tr style="background-color: <?php echo $this->cycle()->prev() ?>">
       <td><?php echo $this->escapeHtml($book['author']) ?></td>
    </tr>
    <?php endforeach ?>
</table>
```

The output:

```php
<table>
    <tr style="background-color: #FFF">
        <td>First</td>
    </tr>
    <tr style="background-color: #F0F0F0">
        <td>Second</td>
    </tr>
</table>
```

## Working with two or more cycles

To use two cycles you have to specify the names of cycles. Just set second parameter in cycle
method: `$this->cycle(array('#F0F0F0', '#FFF'), 'cycle2')`

```php
<table>
    <?php foreach ($this->books as $book): ?>
        <tr style="background-color: <?php echo $this->cycle(array('#F0F0F0', '#FFF'))
                                                     ->next() ?>">
            <td><?php echo $this->cycle(array(1, 2, 3), 'number')->next() ?></td>
            <td><?php echo $this->escapeHtml($book['author']) ?></td>
        </tr>
    <?php endforeach ?>
</table>
```

You can also use `assign($data, $name)` and `setName($name)` methods:

```php
<?php
$this->cycle()->assign(array('#F0F0F0', '#FFF'), 'colors');
$this->cycle()->assign(array(1, 2, 3), 'numbers');
?>
<table>
    <?php foreach ($this->books as $book): ?>
        <tr style="background-color: <?php echo $this->cycle()->setName('colors')->next() ?>">
            <td><?php echo $this->cycle()->setName('numbers')->next() ?></td>
            <td><?php echo $this->escapeHtml($book['author']) ?></td>
        </tr>
    <?php endforeach ?>
</table>
```
