<?php
class Servicio {
    private $idServicio;
    private $titulo;
    private $categoria;
    private $descripcion;
    private $precio;
    private $departamento;
    private $imagen;
    private $duracion; 

    public function __construct(
        $idServicio = null,
        $titulo = '',
        $categoria = '',
        $descripcion = '',
        $precio = 0,
        $departamento = '',
        $imagen = '',
        $duracion = 0 
    ) {
        $this->idServicio = $idServicio;
        $this->titulo = $titulo;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->departamento = $departamento;
        $this->imagen = $imagen;
        $this->duracion = $duracion; 
    }

    // Getters
    public function getIdServicio() { return $this->idServicio; }
    public function getTitulo() { return $this->titulo; }
    public function getCategoria() { return $this->categoria; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getDepartamento() { return $this->departamento; }
    public function getImagen() { return $this->imagen; }
    public function getDuracion() { return $this->duracion; } 

    // Setters
    public function setIdServicio($idServicio) { $this->idServicio = $idServicio; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setDepartamento($departamento) { $this->departamento = $departamento; }
    public function setImagen($imagen) { $this->imagen = $imagen; }
    public function setDuracion($duracion) { $this->duracion = $duracion; } 

    // Guardar en base de datos
    public function guardar($conn) {
        $sql = "INSERT INTO Servicio (titulo, categoria, descripcion, precio, departamento, imagen, duracion) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "sssdssi",
            $this->titulo,
            $this->categoria,
            $this->descripcion,
            $this->precio,
            $this->departamento,
            $this->imagen,
            $this->duracion
        );

        if ($stmt->execute()) {
            return true;
        } else {
            die("Error al guardar servicio: " . $stmt->error);
        }
    }

    // Obtener servicio por ID
    public static function obtenerPorId($conn, $idServicio) {
        $sql = "SELECT * FROM Servicio WHERE IdServicio = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            return new Servicio(
                $row['IdServicio'] ?? null,
                $row['Titulo'] ?? '',
                $row['Categoria'] ?? '',
                $row['Descripcion'] ?? '',
                $row['Precio'] ?? 0,
                $row['departamento'] ?? '',
                $row['imagen'] ?? '',
                $row['Duracion'] ?? 0
            );
        }

        return null;
    }

    // Actualizar un servicio
    public function actualizar($conn) {
        $sql = "UPDATE servicio SET 
                    Titulo = ?, 
                    Categoria = ?, 
                    Descripcion = ?, 
                    Precio = ?, 
                    departamento = ?, 
                    imagen = ?,
                    duracion = ?
                WHERE idServicio = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssdisii",
            $this->titulo,
            $this->categoria,
            $this->descripcion,
            $this->precio,
            $this->departamento,
            $this->imagen,
            $this->duracion,
            $this->idServicio
        );

        return $stmt->execute();
    }

    
    public static function buscarServicios($conn, $q = '', $departamento = '', $estrellas = 0) {
        $categorias = ['Hogar','Autos','Belleza','Cuidado de niños','Digital','Cocina','Salud','Mascotas','Eventos','Educación','Transporte','Arte y Cultura'];

        $where = [];

        // por palabra
        if (!empty($q)) {
            $q_escapado = $conn->real_escape_string($q);
            $categoriaCoincide = false;

            foreach ($categorias as $cat) {
                if (strcasecmp($q, $cat) === 0) {
                    $categoriaCoincide = true;
                    $where[] = "LOWER(s.Categoria) = LOWER('$cat')";
                    break;
                }
            }

            if (!$categoriaCoincide && strlen($q) >= 3) {
                $where[] = "(s.Titulo LIKE '%$q_escapado%' OR s.Descripcion LIKE '%$q_escapado%')";
            }
        }

        //  por departamento
        if (!empty($departamento)) {
            $departamento_escapado = $conn->real_escape_string($departamento);
            $where[] = "LOWER(s.Departamento) = LOWER('$departamento_escapado')";
        }

        $sql = "
            SELECT 
                s.*,
                COALESCE(ROUND(AVG(c.Calificacion),1), 0) AS promedio_calificacion
            FROM Servicio s
            LEFT JOIN Contrata c ON s.IdServicio = c.IdServicio
        ";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " GROUP BY s.IdServicio";

        if ($estrellas > 0) {
            $sql .= " HAVING ROUND(promedio_calificacion) = $estrellas";
        }

        $sql .= " ORDER BY promedio_calificacion DESC";

        $result = $conn->query($sql);
        if (!$result) {
            die("Error en la consulta: " . $conn->error);
        }

        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
?>
