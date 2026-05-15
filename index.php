<?php
session_start();
require 'db.php';

if(!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }
if(isset($_GET['logout'])) { session_destroy(); header("Location: login.php"); exit; }

$es_admin = ($_SESSION['usuario'] === 'admin');

// --- ACCIÓN ADMIN: ELIMINAR JUEGO ---
if($es_admin && isset($_GET['delete_game'])) {
    $id_del = intval($_GET['delete_game']);
    $stmt = $pdo->prepare("DELETE FROM juegos WHERE id = ?");
    $stmt->execute([$id_del]);
    header("Location: index.php");
    exit;
}

// --- ACCIÓN ADMIN: INSERTAR JUEGO ---
if($es_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_juego'])) {
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $desc = $_POST['descripcion'];
    $img = $_POST['imagen'];
    $galeria = json_encode([$img, $_POST['img2'], $_POST['img3'], $_POST['img4']]);
    
    $stmt = $pdo->prepare("INSERT INTO juegos (nombre, genero, descripcion, imagen, galeria) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $genero, $desc, $img, $galeria]);
    header("Location: index.php");
    exit;
}

// --- ACCIÓN: ELIMINAR REVIEW (Usuario o Admin) ---
if(isset($_GET['delete_review']) && isset($_GET['id'])) {
    $id_rev = intval($_GET['delete_review']);
    $id_juego_ref = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT usuario FROM reviews WHERE id = ?");
    $stmt->execute([$id_rev]);
    $review_data = $stmt->fetch();

    if($review_data) {
        if($es_admin || $_SESSION['usuario'] === $review_data['usuario']) {
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$id_rev]);
        }
    }
    header("Location: index.php?id=$id_juego_ref");
    exit;
}

// --- CARGAR JUEGOS DESDE BD ---
$query = $pdo->query("SELECT * FROM juegos");
$videojuegos = $query->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);

$filtroGenero = $_GET['genero'] ?? null;
$idJuego = isset($_GET['id']) ? intval($_GET['id']) : null;

// --- LÓGICA DE REVIEWS (Insertar / Editar) ---
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id_juego'], $_POST['nuevo_comentario'], $_POST['calificacion'])) {
    $idJ = intval($_POST['id_juego']);
    $texto = trim($_POST['nuevo_comentario']);
    $cal = intval($_POST['calificacion']);
    $user = $_SESSION['usuario'];

    if($texto && $cal>=1 && $cal<=10) {
        $stmt = $pdo->prepare("INSERT INTO reviews (id_juego, usuario, texto, calificacion) VALUES (?, ?, ?, ?) 
                                ON DUPLICATE KEY UPDATE texto = VALUES(texto), calificacion = VALUES(calificacion)");
        $stmt->execute([$idJ, $user, $texto, $cal]);
    }
    header("Location: index.php?id=$idJ");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>pop_games - Pro</title>
    <link rel="stylesheet" href="index.css?v=1.1">
</head>
<body>

<div class="header">
    <h1>pop_games</h1>
    <div class="menu">
        <a href="index.php">Todos</a>
        <a href="?genero=Aventura">Aventura</a>
        <a href="?genero=RPG">RPG</a>
        <a href="?genero=Sandbox">Sandbox</a>
        <a href="?genero=Deportes">Deportes</a>
        <span style="margin-left:20px;">
            Bienvenido, <b><?php echo htmlspecialchars($_SESSION['usuario']); ?></b> | <a href="?logout">Salir</a>
        </span>
    </div>
</div>

<?php if($es_admin && !$idJuego): ?>
<div class="container" style="background:#1e1e1e; padding:15px; border-radius:8px; margin-top:20px; color:white;">
    <h3>Admin: Añadir nuevo juego</h3>
    <form method="post">
        <input type="hidden" name="nuevo_juego" value="1">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="genero" placeholder="Género" required>
        <input type="text" name="imagen" placeholder="Carpeta/imagen.webp" required>
        <input type="text" name="img2" placeholder="Galería 2">
        <input type="text" name="img3" placeholder="Galería 3">
        <input type="text" name="img4" placeholder="Galería 4">
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <button type="submit">Guardar Juego en BD</button>
    </form>
</div>
<?php endif; ?>

<?php
if($idJuego && isset($videojuegos[$idJuego])) {
    $j = $videojuegos[$idJuego];
    $galeria = json_decode($j['galeria'] ?? '[]', true);
    $mi_usuario = $_SESSION['usuario'];

    $stmt_mi_rev = $pdo->prepare("SELECT * FROM reviews WHERE id_juego = ? AND usuario = ?");
    $stmt_mi_rev->execute([$idJuego, $mi_usuario]);
    $mi_review = $stmt_mi_rev->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id_juego = ? ORDER BY fecha DESC");
    $stmt->execute([$idJuego]);
    $reviewsJuego = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $promedio = 0;
    if(count($reviewsJuego) > 0){
        $total = 0;
        foreach($reviewsJuego as $r) $total += $r['calificacion'];
        $promedio = round($total / count($reviewsJuego), 1);
    }

    echo "<div class='container detalle-container'>";
    echo "<div class='detalle'>";
    echo "<h2>{$j['nombre']}</h2>";
    echo "<img src='{$j['imagen']}' style='max-width:100%; border-radius:10px;'>";
    echo "<p><strong>Género:</strong> {$j['genero']}</p>";
    echo "<p><strong>Descripción:</strong></p><p>{$j['descripcion']}</p>";
    echo "<p><strong>Calificación promedio:</strong> " . ($promedio > 0 ? "⭐ $promedio" : "Sin reviews") . "</p>";
    echo "<a href='index.php' class='back'>Volver a la lista</a>";
    echo "</div>";

    echo "<div class='comentarios-container'><h3>Reviews de Usuarios</h3>";
    
    $texto_edit = $mi_review ? htmlspecialchars($mi_review['texto']) : "";
    $nota_edit = $mi_review ? $mi_review['calificacion'] : 10;
    ?>
    <form method="post" class="form-review" style="margin-bottom:20px; background: #252525; padding: 15px; border-radius: 8px;">
        <p style="color: #ffa500; margin-bottom: 10px;">
            <?php echo $mi_review ? "Estas editando tu reseña anterior:" : "Deja tu reseña:"; ?>
        </p>
        <input type="hidden" name="id_juego" value="<?php echo $idJuego; ?>">
        <textarea name="nuevo_comentario" placeholder="Escribe tu opinión..." required style="width:100%; min-height:60px;"><?php echo $texto_edit; ?></textarea><br>
        <label>Calificación: </label>
        <select name="calificacion">
            <?php 
            for($i=10;$i>=1;$i--) {
                $sel = ($i == $nota_edit) ? "selected" : "";
                echo "<option value='$i' $sel>$i ★</option>";
            }
            ?>
        </select>
        <button type="submit"><?php echo $mi_review ? "Actualizar Review" : "Publicar Review"; ?></button>
    </form>
    <?php
    foreach($reviewsJuego as $r){
        $stars = intval($r['calificacion']);
        $es_propietario = ($_SESSION['usuario'] === $r['usuario']);

        echo "<div class='comentario' style='border-bottom: 1px solid #444; padding: 15px 0;'>";
        
        // --- NOMBRE Y PUNTUACIÓN RESALTADOS ---
        echo "<span class='user-name'>".htmlspecialchars($r['usuario'])."</span>";
        echo "<span class='score-badge'>$stars/10</span> ";
        echo "<span class='stars-color'>";
        for($i=0;$i<$stars;$i++){ echo "★"; }
        echo "</span>";
        
        // --- TEXTO DEL COMENTARIO ---
        echo "<p class='review-text'>\"".htmlspecialchars($r['texto'])."\"</p>";

        if($es_propietario || $es_admin) {
            $msg = $es_admin ? "¿Eliminar como admin?" : "¿Borrar tu comentario?";
            echo "<div style='margin-top:10px;'>";
            echo "<a href='?id=$idJuego&delete_review={$r['id']}' style='color:#ff4d4d; text-decoration:none; font-size:12px; background: rgba(255,77,77,0.1); padding: 3px 6px; border-radius: 3px;' onclick='return confirm(\"$msg\")'>🗑️ Eliminar</a>";
            echo "</div>";
        }
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='galeria-container'><h3>Galería de Imágenes</h3><div class='galeria' style='display:flex; gap:10px; flex-wrap:wrap;'>";
    foreach($galeria as $img) { 
        if($img) echo "<img src='$img' style='width:200px; height:120px; object-fit:cover; border-radius:5px;'>"; 
    }
    echo "</div></div></div>";

} else {
    echo "<div class='container' style='display:flex; flex-wrap:wrap; gap:20px;'>";
    foreach($videojuegos as $id => $j){
        if(!$filtroGenero || $j['genero'] == $filtroGenero){
            echo "<div class='juego' style='width:200px; text-align:center;'>";
            echo "<a href='?id=$id'><img src='{$j['imagen']}' style='width:100%; border-radius:8px;'></a><br>";
            echo "<a href='?id=$id' style='text-decoration:none; color:white; font-weight:bold;'>{$j['nombre']}</a>";
            if($es_admin) {
                echo "<br><a href='?delete_game=$id' style='color:#ff4d4d; font-size:12px;' onclick='return confirm(\"¿Estás seguro de eliminar este juego?\")'>[Eliminar Juego]</a>";
            }
            echo "</div>";
        }
    }
    echo "</div>";
}
?>
</body>
</html>