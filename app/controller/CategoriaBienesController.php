<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/CategoriaBienes.php';

class CategoriaBienesController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoriaBienes();
    }

    public function showCategorias()
    {
        $nombre = $this->checkLogin();
        $categorias = $this->model->getAllCategorias();

        $this->loadView(
            'configuracion.categoriaBienes',
            [
                'categorias' => $categorias,
                'nombre' => $nombre
            ],
            [
                '/gestion/app/view/configuracion/recursos/css/categoriaBienes.min.css'
            ],
            [
                '/gestion/app/view/configuracion/recursos/js/categoriaBienes.min.js'
            ],
            'Gestión de Categorías de Bienes'
        );
    }

    public function crearCategoria()
    {
        $nombre = $_POST['nombre'] ?? null;

        if (!$nombre) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre de la categoría es obligatorio.']);
            exit;
        }

        $this->model->crearCategoria($nombre);

        echo json_encode(['status' => 'success', 'message' => 'Categoría creada exitosamente.']);
    }

    public function editarCategoria()
    {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;

        if (!$id || !$nombre) {
            echo json_encode(['status' => 'error', 'message' => 'ID y nombre de la categoría son obligatorios.']);
            exit;
        }

        $this->model->editarCategoria($id, $nombre);

        echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada exitosamente.']);
    }

    public function eliminarCategoria()
{
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'El ID de la categoría es obligatorio.']);
        exit;
    }

    if ($this->model->categoriaEnUso($id)) {
        echo json_encode(['status' => 'error', 'message' => 'No se puede eliminar la categoría porque tiene bienes asociados.']);
        exit;
    }

    $this->model->eliminarCategoria($id);

    echo json_encode(['status' => 'success', 'message' => 'Categoría eliminada exitosamente.']);
}

    
}

$action = $_GET['action'] ?? 'showCategorias';
$controller = new CategoriaBienesController();
$controller->$action();
