<?php
$host = 'mysql-7fbb'; 
$db   = 'popgames_db';
$user = 'jorge';
$pass = 'Uunf62DbeQeBwVpGptgT0X1K9p1N4v4m';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tu archivo SQL estructurado de forma limpia
    $query = "
    CREATE TABLE IF NOT EXISTS `juegos` (
      `id` int NOT NULL AUTO_INCREMENT,
      `nombre` varchar(100) NOT NULL,
      `genero` varchar(50) DEFAULT NULL,
      `descripcion` text,
      `imagen` varchar(255) DEFAULT NULL,
      `galeria` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `usuarios` (
      `id` int NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      `nombre_completo` varchar(100) DEFAULT NULL,
      `apellido_completo` varchar(100) DEFAULT NULL,
      `correo` varchar(100) DEFAULT NULL,
      `codigo_verificacion` varchar(10) DEFAULT NULL,
      `verificado` tinyint(1) DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`),
      UNIQUE KEY `correo` (`correo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `reviews` (
      `id` int NOT NULL AUTO_INCREMENT,
      `id_juego` int NOT NULL,
      `usuario` varchar(50) NOT NULL,
      `texto` text NOT NULL,
      `calificacion` int NOT NULL,
      `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `id_juego` (`id_juego`),
      CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    REPLACE INTO `juegos` (`id`, `nombre`, `genero`, `descripcion`, `imagen`, `galeria`) VALUES
    (1, 'Zelda: Breath of the Wild', 'Aventura', 'Aventura de mundo abierto donde Link despierta tras 100 años...', 'imagenes/zelda.webp', '[\"imagenes/zelda.webp\",\"imagenes/zelda2.webp\"]'),
    (2, 'Uncharted 4', 'Aventura', 'Nathan Drake regresa a la búsqueda de un legendario tesoro pirata...', 'imagenes/uncharted.webp', '[\"imagenes/uncharted.webp\",\"imagenes/uncharted2.webp\",\"imagenes/uncharted3.webp\",\"imagenes/uncharted4.webp\"]'),
    (3, 'Tomb Raider', 'Aventura', 'Lara Croft debe sobrevivir en una isla hostil tras un naufragio...', 'imagenes/tombraider.webp', '[\"imagenes/tombraider.webp\",\"imagenes/tombraider2.webp\",\"imagenes/tombraider3.webp\",\"imagenes/tombraider4.webp\"]'),
    (4, 'Assassin\'s Creed Valhalla', 'Aventura', 'Controlas a Eivor, un vikingo que lidera a su clan desde Noruega...', 'imagenes/acvalhalla.webp', '[\"imagenes/acvalhalla.webp\",\"imagenes/acvalhalla2.webp\",\"imagenes/acvalhalla3.webp\",\"imagenes/acvalhalla4.webp\"]'),
    (5, 'Cyberpunk 2077', 'RPG', 'En Night City encarnas a V, un mercenario que intenta sobrevivir...', 'imagenes/cyberpunk.webp', '[\"imagenes/cyberpunk.webp\",\"imagenes/cyberpunk2.webp\",\"imagenes/cyberpunk3.webp\",\"imagenes/cyberpunk4.webp\"]'),
    (6, 'The Witcher 3', 'RPG', 'Geralt de Rivia recorre un vasto mundo abierto cazando monstruos...', 'imagenes/witcher3.webp', '[\"imagenes/witcher3.webp\",\"imagenes/witcher32.webp\",\"imagenes/witcher33.webp\",\"imagenes/witcher34.webp\"]'),
    (7, 'Persona 5', 'RPG', 'Un estudiante vive una doble vida como ladrón fantasma...', 'imagenes/persona5.webp', '[\"imagenes/persona5.webp\",\"imagenes/persona52.webp\",\"imagenes/persona53.webp\",\"imagenes/persona54.webp\"]'),
    (8, 'Dragon Age: Inquisition', 'RPG', 'Lideras la Inquisición para salvar al mundo de una catáctrofe mágica...', 'imagenes/dragonage.webp', '[\"imagenes/dragonage.webp\",\"imagenes/dragonage2.webp\",\"imagenes/dragonage3.webp\",\"imagenes/dragonage4.webp\"]'),
    (9, 'Minecraft', 'Sandbox', 'Un sandbox de construcción y supervivencia donde puedes crear mundos...', 'imagenes/minecraft.webp', '[\"imagenes/minecraft.webp\",\"imagenes/minecraft2.webp\",\"imagenes/minecraft3.webp\",\"imagenes/minecraft4.webp\"]'),
    (10, 'Terraria', 'Sandbox', 'Aventura 2D de exploración, construcción y combate...', 'imagenes/terraria.webp', '[\"imagenes/terraria.webp\",\"imagenes/terraria2.webp\",\"imagenes/terraria3.webp\",\"imagenes/terraria4.webp\"]'),
    (11, 'Roblox', 'Sandbox', 'Plataforma donde millions de usuarios crean sus propios juegos...', 'imagenes/roblox.webp', '[\"imagenes/roblox.webp\",\"imagenes/roblox2.webp\",\"imagenes/roblox3.webp\",\"imagenes/roblox4.webp\"]'),
    (12, 'The Sims 4', 'Sandbox', 'Simulador de vida donde creas personajes y construyes casas...', 'imagenes/sims4.webp', '[\"imagenes/sims4.webp\",\"imagenes/sims42.webp\",\"imagenes/sims43.webp\",\"imagenes/sims44.webp\"]'),
    (13, 'FIFA 23', 'Deportes', 'Simulador de fútbol realista con equipos y ligas oficiales...', 'imagenes/fifa23.webp', '[\"imagenes/fifa23.webp\",\"imagenes/fifa232.webp\",\"imagenes/fifa233.webp\",\"imagenes/fifa234.webp\"]'),
    (14, 'NBA 2K23', 'Deportes', 'Juego de baloncesto que recrea la NBA con gráficos realistas...', 'imagenes/nba2k23.webp', '[\"imagenes/nba2k23.webp\",\"imagenes/nba2k232.webp\",\"imagenes/nba2k233.webp\",\"imagenes/nba2k234.webp\"]'),
    (15, 'Madden NFL 23', 'Deportes', 'Simulador de fútbol americano con equipos oficiales de la NFL...', 'imagenes/madden23.webp', '[\"imagenes/madden23.webp\",\"imagenes/madden232.webp\",\"imagenes/madden233.webp\",\"imagenes/madden234.webp\"]'),
    (16, 'F1 23', 'Deportes', 'Simulador oficial de Fórmula 1 con todos los circuitos y pilotos...', 'imagenes/f123.webp', '[\"imagenes/f123.webp\",\"imagenes/f1232.webp\",\"imagenes/f1233.webp\",\"imagenes/f1234.webp\"]);

    REPLACE INTO `usuarios` (`id`, `username`, `password`, `nombre_completo`, `apellido_completo`, `correo`, `codigo_verificacion`, `verificado`) VALUES
    (1, 'admin', '1234', NULL, NULL, NULL, NULL, 0),
    (2, 'jorge', 'j1234', NULL, NULL, NULL, NULL, 0),
    (3, 'hector', 'h1234', NULL, NULL, NULL, NULL, 0),
    (4, 'samuel', 's1234', NULL, NULL, NULL, NULL, 0);

    REPLACE INTO `reviews` (`id`, `id_juego`, `usuario`, `texto`, `calificacion`, `fecha`) VALUES
    (1, 1, 'jorge', 'Esta guapo este juego', 10, '2026-05-13 21:37:18'),
    (2, 1, 'samuel', 'Estoy de acuerdo contigo jorge', 10, '2026-05-13 21:38:40');
    ";

    // Ejecutamos usando el driver nativo permitiendo múltiples sentencias
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    $pdo->exec($query);
    
    echo "<h1 style='color:green; font-family:sans-serif;'>¡ÉXITO TOTAL! Las tablas de Pop Games están listas.</h1>";
    echo "<p>Ya puedes ir a la página principal.</p>";

} catch (PDOException $e) {
    echo "<h1 style='color:red; font-family:sans-serif;'>Fallo en la base de datos:</h1> " . $e->getMessage();
}
?>
