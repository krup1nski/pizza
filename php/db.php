<?php

session_start();
require "connection.php";

// для удобного чтения
function tt($value)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}


function select_all($table){
    global $pdo;
    $sql = "SELECT * FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt;
}

function select_one($table, $id){
    global $pdo;
    $sql = "SELECT * FROM $table WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

function selectDoughs($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                d.name AS dough_name
            FROM 
                pizzas p
            JOIN 
                `pizzas-doughs` pd ON p.id = pd.id_pizza
            JOIN 
                doughs d ON pd.id_dough = d.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectSizes($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                sz.name AS size_name,
                sz.price AS size_price,
                sz.gramm AS gramm_price
            FROM 
                pizzas p
            JOIN 
                `pizzas-sizes` ps ON p.id = ps.id_pizza
            JOIN 
                sizes sz ON ps.id_size = sz.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectSides($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                sd.name AS side_name,
                sd.price AS side_price
            FROM 
                pizzas p
            JOIN 
                `pizzas-sides` psd ON p.id = psd.id_pizza
            JOIN 
                sides sd ON psd.id_side = sd.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


