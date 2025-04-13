<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, DELETE,PUT, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Include database connection
require "../../koneksi.php";

// Get the HTTP method and request URI
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Function to fetch all categories
function get_categories($db)
{
    $query = "SELECT * FROM tbl_kat_produk";
    $result = mysqli_query($db, $query);
    $categories = [];

    while ($category = mysqli_fetch_array($result)) {
        $categories[] = $category;
    }

    if (!empty($categories)) {
        echo json_encode($categories);
    } else {
        echo json_encode(["message" => "No categories found"]);
    }
}

// Function to fetch category by ID
function get_category_by_id($db, $id)
{
    $query = "SELECT * FROM tbl_kat_produk WHERE id_kategori = '$id'";
    $result = mysqli_query($db, $query);
    $category = mysqli_fetch_array($result);

    if ($category) {
        echo json_encode($category);
    } else {
        echo json_encode(["message" => "Category not found"]);
    }
}

// Function to create a new category
function create_category($db, $name)
{
    $query = "INSERT INTO tbl_kat_produk (nm_kategori) VALUES ('$name')";
    $result = mysqli_query($db, $query);

    if ($result) {
        echo json_encode(["message" => "Category created successfully"]);
    } else {
        echo json_encode(["message" => "Failed to create category"]);
    }
}

// Function to update a category by ID
function update_category($db, $id, $name)
{
    $query = "UPDATE tbl_kat_produk SET nm_kategori = '$name' WHERE id_kategori = '$id'";
    $result = mysqli_query($db, $query);

    if ($result) {
        echo json_encode(["message" => "Category updated successfully"]);
    } else {
        echo json_encode(["message" => "Failed to update category"]);
    }
}

// Function to delete a category by ID
function delete_category($db, $id)
{
    $query = "DELETE FROM tbl_kat_produk WHERE id_kategori = '$id'";
    $result = mysqli_query($db, $query);

    if ($result) {
        echo json_encode(["message" => "Category deleted successfully"]);
    } else {
        echo json_encode(["message" => "Failed to delete category"]);
    }
}

// Route handling
if ($request_method == 'GET') {
    // Check if we are fetching a category by ID
    if (isset($_GET['id'])) {
        // Get category by ID
        $id = $_GET['id'];
        get_category_by_id($db, $id);
    } else {
        // Get all categories
        get_categories($db);
    }
} elseif ($request_method == 'POST') {
    // Create a new category
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['nama'])) {
        $name = $data['nama'];
        create_category($db, $name);
    } else {
        echo json_encode(["message" => "Category nama is required"]);
    }
} elseif ($request_method == 'PUT' && isset($_GET['id'])) {
    // Update category by ID
    if (isset($_GET['id']) && isset($_POST['nama'])) {
        $id = $_GET['id'];
        $name = $_POST['nama'];
        update_category($db, $id, $name);
    } else {
        echo json_encode(["message" => "Category ID and new name are required"]);
    }
} elseif ($request_method == 'DELETE' && isset($_GET['id'])) {
    // Delete category by ID
    $id = $_GET['id'];
    delete_category($db, $id);
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>