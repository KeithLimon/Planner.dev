<?php  
define('FILENAME', 'data/list.txt');
/* -------------------------------------- */
 
// OPEN FILE TO POPULATE LIST
 
function open_file($filename = FILENAME) {
 
    $handle = fopen($filename, 'r');
    $contents = trim(fread($handle, filesize($filename)));
    $list = explode("\n", $contents);
    fclose($handle);
    return $list;
}
 
/* -------------------------------------- */
 
// SAVE LIST TO FILENAME
 
function save_to_file($list, $filename = FILENAME) {
 
    $handle = fopen($filename, 'w');
    foreach ($list as $item) {
        fwrite($handle, $item . PHP_EOL);
    }
    fclose($handle);        
}
 
/* -------------------------------------- */
    $items= open_file();
    
    if (isset($_POST['additem'])) {
        //var_dump($_POST);
        $items[] = $_POST['additem'];
        save_to_file($items);
    }
    
    // if there is a get request to remove a key,
    // unset that key from the array
    // save.
    
    if (isset($_GET['remove'])) {
        $keyRemove = $_GET['remove'];
        unset($items[$keyRemove]);
        $items = array_values($items);
        save_to_file($items);
        
    }
     // Verify there were uploaded files and no errors
    if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
        // Grab the filename from the uploaded file by using basename
        $filename = basename($_FILES['file1']['name']);
        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $filename;
        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
        $uploadedlist = open_file($saved_filename);
        $items = array_merge($uploadedlist, $items);
        save_to_file($items);
    }

     
        if (isset($saved_filename)) {
            
        echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
    }
    
    
?>

<html>
<head>
    <title>ToDo List!!</title>
</head>
<body>
    
    <h1>This is My Todo List App!</h1>

    <ul>
        
            <?php foreach ($items as $key => $item) : ?>
                <li><a href="?remove=<?= $key; ?>">Remove</a> - <?= htmlspecialchars(strip_tags($item)); ?></li>
            <?php endforeach; ?>

    </ul>
    
    s
    <!-- Form to allow items to be added -->
    <form name="additem" method="POST" action="/todo_web/todo_list.php">
     
        <label>Add Item: </label>
        <input type="text" id="additem" name="additem">
        <button value="submit">Add Item</button>
     
    </form>
    
    <h1>Upload File</h1>
    
        <form method="POST" enctype="multipart/form-data" action="/todo_web/todo_list.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>

    
    
    

</body>
</html>
