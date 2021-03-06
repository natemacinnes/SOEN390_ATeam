# Code style

## Indentation
This project uses tabs for indentation so that each user may configure their
editor to their preferred whitespace width.


## Code blocks
Braces are to be placed on their own lines, for example:

    Conditionals:
        if (condition)
        {
            // conditional code
        }

    Looping:
        foreach (condition)
        {
            // looped code
        }

Include curly braces for *all* conditionals, even if they are one line:

    if (condition)
    {
      // code to execute
    }

Do not use PHP's single-line-if syntax:

    if (condition) code to execute;


## Visual spacing
Include a single spaces between control structures and parenthesis,
concatenation, assignment and addition/subtraction operators for better visual
spacing and readability. This means that statements like ```if```, ```if else```, ```foreach```,
etc look like this when written:

    if (condition1 || condition2)

Instead of the more difficult to read:

    if(condition1||condition2)


Concatenation looks like this:

    $url = base_url() . 'admin/narrative/' . $narrative_id . '/edit';

Instead of the more difficult to read:

    $url = base_url().'admin/narrative/'.$narrative_id.'/edit';


Math operations have terms separated by whitespace like this:

    $popular = ($agree - $disagree)/2 + $age

Instead of the more difficult to read:

    $popular = ($agree-$disagree)/2+$age


## Inline PHP
When programming models, avoid large blocks of PHP as much as possible.

Views should be structured so that PHP can be sprinkled into existing HTML using
inline ```<?php echo $value; ?>``` snippets, for example:

    <input type="text" name="email" value="<?php echo $email; ?> />

If conditionals are required, use the inline syntax instead of curly braces:

    <?php if (condition): ?>
        <!-- html code -->
    <?php else: ?>
        <!-- html code -->
    <?php endif; ?>

You can use a similar syntax for looping:

    <?php foreach ($loop as $item): ?>
        <!-- html code -->
    <?php endforeach; ?>



# Class names

## Naming style
All class names (models, controllers, etc) should be written in ```Title_Case``` in
accordance with CI convention. Note that all files are stored in lowercase on
the filesystem to ensure proper loading of class files on platforms with case
sensitive filesystems (e.g. Linux).

For models, suffix every class name with ```_Model``` to ensure that class names of
controllers for the same function don't conflict - for example a ```Narrative```
controller and a ```Narrative``` model. Use ```Narrative_Model``` for the model.


## Namespacing
Any class name extending a core class must begin with the application namespace,
```YD_``` to avoid conflicts
