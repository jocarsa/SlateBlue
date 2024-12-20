<?php
function parseDirectory($directory, $level = 1) {
    $output = '';
    $items = scandir($directory);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            $heading = "h" . min($level, 6); // Limit to h1-h6
            $id = strtolower(str_replace(' ', '-', $item)); // Create a valid ID
            $id = preg_replace('/[^a-z0-9\-]/', '', $id); // Remove invalid characters
            $output .= "<{$heading} id=\"{$id}\" ";
            if($heading == 2){
            	$output .= "class='chapter'";
            }
            $output .= " >{$item}</{$heading}>\n";
            $output .= parseDirectory($path, $level + 1); // Recursive call for subdirectories
        } elseif (is_file($path)) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); // Get file extension
            $filename = htmlspecialchars($item); // Sanitize the file name
            $output .= "<p><strong>Archivo: {$filename}</strong></p>\n"; // Display file name
            
            if (in_array($ext, ['py', 'js', 'php', 'html', 'css'])) {
                $content = htmlspecialchars(file_get_contents($path)); // Sanitize file content
                $output .= "<pre>{$content}</pre>\n";
            } elseif ($ext === 'txt') {
                $content = nl2br(htmlspecialchars(file_get_contents($path)));
                $output .= "<p>{$content}</p>\n";
            } elseif (in_array($ext, ['jpg', 'png', 'svg'])) {
                $relativePath = htmlspecialchars(str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $path));
                $output .= "<img src=\"{$relativePath}\" alt=\"{$filename}\" style=\"max-width:100%; height:auto;\">\n";
            }
        }
    }
    return $output;
}

function generateTableOfContents($directory, $level = 1) {
    $output = '';
    $items = scandir($directory);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            $id = strtolower(str_replace(' ', '-', $item)); // Create a valid ID
            $id = preg_replace('/[^a-z0-9\-]/', '', $id); // Remove invalid characters
            $indent = str_repeat('&nbsp;', ($level - 1) * 4); // Indent based on level
            $output .= "{$indent}<a href=\"#{$id}\">{$item}</a><br>\n";
            $output .= generateTableOfContents($path, $level + 1); // Recursive call for subdirectories
        }
    }
    return $output;
}

$rootDirectory = __DIR__ . '/materiales'; // Root directory for parsing
$title = "Python 3.13"; // Title of the document
$tableOfContents = generateTableOfContents($rootDirectory); // Generate TOC
$content = parseDirectory($rootDirectory); // Generate content

// Output the complete HTML document
echo "<!DOCTYPE html>
<html>
<head>
    <title>{$title}</title>
    <link rel='stylesheet' href='estilos.css'>
";?>
<style>
	/* General Styles */
body {
    font-family: 'Roboto', Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: white; /* Fondo blanco para impresión */
    color: #333;
}

h1, h2, h3, h4, h5, h6 {
    margin: 1.5em 0 0.5em;
    font-weight: bold;
}

h1 {
    font-size: 2.5em;
    text-align: center;
    color: #2c3e50;
}

h2 {
    font-size: 2em;
    border-bottom: 2px solid #e67e22;
    padding-bottom: 0.3em;
    color: #e67e22;
}

h3 {
    font-size: 1.8em;
    color: #3498db;
}

h4, h5, h6 {
    font-size: 1.5em;
    color: #16a085;
}

a {
    color: #e67e22;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

p {
    margin: 0.8em 0;
}

/* Table of Contents */
#table-of-contents {
    margin: 20px 0;
    padding: 10px;
    background-color: #f9f9f9; /* Fondo claro para impresión */
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    font-size: 0.95em;
}

#table-of-contents a {
    display: block;
    margin: 5px 0;
    padding: 5px;
    color: #2980b9;
    text-decoration: none;
}

/* Indentation for hierarchy levels using margin-left */
#table-of-contents .level-1 {
    margin-left: 0; /* No indent */
    font-weight: bold;
}

#table-of-contents .level-2 {
    margin-left: 20px; /* Indent for level 2 */
}

#table-of-contents .level-3 {
    margin-left: 40px; /* Further indent for level 3 */
}

#table-of-contents .level-4 {
    margin-left: 60px; /* Further indent for level 4 */
}

#table-of-contents a:hover {
    background-color: #d5d8dc;
    border-radius: 3px;
    color: #000;
}

/* Preformatted Code Section with Window Decoration */
pre {
    background-color: #2d2d2d;
    color: #f8f8f2;
    padding: 1em;
    border-radius: 8px;
    overflow-x: auto;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.95em;
    line-height: 1.4;
    position: relative;
    margin: 1em 0;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

pre::before {
    content: "";
    display: block;
    background: #444;
    height: 24px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    position: absolute;
    top: -24px;
    left: 0;
    right: 0;
}

pre .window-title {
    position: absolute;
    top: -24px;
    left: 10px;
    font-size: 0.9em;
    color: #fff;
    font-weight: bold;
    line-height: 24px;
}

pre .window-buttons {
    position: absolute;
    top: -20px;
    right: 10px;
    display: flex;
    gap: 5px;
}

pre .window-buttons span {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

pre .window-buttons .close {
    background: #ff5f56;
}

pre .window-buttons .minimize {
    background: #ffbd2e;
}

pre .window-buttons .maximize {
    background: #27c93f;
}

pre code {
    font-family: 'Courier New', Courier, monospace;
}

code {
    background-color: #f3f3f3;
    color: #c7254e;
    padding: 0.2em 0.4em;
    border-radius: 4px;
    font-family: 'Courier New', Courier, monospace;
}

@media print {
    body {
        background-color: white;
        color: #000;
    }

    #table-of-contents {
        box-shadow: none;
        border: none;
    }

    a {
        color: #000;
    }

    pre {
        background-color: #f9f9f9;
        color: #000;
        box-shadow: none;
    }

    pre::before {
        background-color: #ccc;
    }

    .window-buttons span {
        background: #999;
    }

    /* Add page breaks */
    h1, h2 {
        page-break-before: always;
    }

    h1:first-of-type {
        page-break-before: auto; /* No break before the first h1 */
    }

    #table-of-contents {
        page-break-before: always;
    }

    .chapter {
        page-break-before: always;
    }
}


</style>
<?php
echo "
</head>
<body>
    <h1>{$title}</h1>
    <h2>Tabla de contenidos</h2>
    <div id=\"table-of-contents\">
        {$tableOfContents}
    </div>
    <h2>Contenido</h2>
    <div id=\"content\">
        {$content}
    </div>
</body>
</html>";
?>

