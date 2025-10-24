<?php


// Format a numeric price as currency (XAF)
function formatPrice($price) {
    return 'XAF ' . number_format((float)$price, 2, '.', ',');
}

function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function calculateTotal($cart) {
    $total = 0.0;
    if (empty($cart) || !is_array($cart)) return formatPrice(0);
    foreach ($cart as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) $total += ((float)$product['price']) * ((int)$quantity);
    }
    return formatPrice($total);
}

function getCartItemCount($cart) {
    return is_array($cart) ? array_sum($cart) : 0;
}

function getFeaturedProducts($limit = 12) {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) return [];
    try {
        $stmt = $pdo->prepare("SELECT id, name, description, price, image FROM products WHERE featured = 1 ORDER BY created_at DESC LIMIT :lim");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getProductById($id) {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) return null;
    $id = (int)$id;
    if ($id <= 0) return null;
    try {
        $stmt = $pdo->prepare("SELECT id, name, description, price, image, category FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

function getProductsByIds(array $ids = []) {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO) || empty($ids)) return [];
    $ids = array_map('intval', array_filter($ids, function($v){ return $v > 0; }));
    if (empty($ids)) return [];
    try {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id, name, description, price, image FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) $out[$r['id']] = $r;
        return $out;
    } catch (PDOException $e) {
        return [];
    }
}

// Return distinct categories from products table
function getCategories() {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) return [];
    try {
        $stmt = $pdo->query("SELECT DISTINCT COALESCE(NULLIF(TRIM(category),''),'') AS cat FROM products WHERE category IS NOT NULL");
        $cats = array_filter(array_map('trim', $stmt->fetchAll(PDO::FETCH_COLUMN)));
        sort($cats);
        return $cats;
    } catch (PDOException $e) {
        return [];
    }
}

// Get all products with optional search, category filter and pagination
function getAllProducts($limit = 24, $offset = 0, $q = null, $category = null) {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) return [];
    $q = trim((string)$q);
    $category = trim((string)$category);
    try {
        $where = [];
        $params = [];

        if ($q !== '') {
            $where[] = "(name LIKE :q OR description LIKE :q)";
            $params[':q'] = '%' . $q . '%';
        }
        if ($category !== '') {
            $where[] = "category = :category";
            $params[':category'] = $category;
        }

        $sql = "SELECT id, name, description, price, image, featured, created_at
                FROM products";
        if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
        $sql .= " ORDER BY created_at DESC LIMIT :lim OFFSET :off";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Count products for pagination with same filters
function getProductCount($q = null, $category = null) {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) return 0;
    $q = trim((string)$q);
    $category = trim((string)$category);
    try {
        $where = [];
        $params = [];
        if ($q !== '') {
            $where[] = "(name LIKE :q OR description LIKE :q)";
            $params[':q'] = '%' . $q . '%';
        }
        if ($category !== '') {
            $where[] = "category = :category";
            $params[':category'] = $category;
        }
        $sql = "SELECT COUNT(*) FROM products";
        if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}
