<?php
require_once '../config/database.php';

// Verificar que el usuario sea un refugio
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'refugio') {
    header("Location: login.php");
    exit();
}

$refugio_id = $_SESSION['user_id'];
?>

<?php require_once '../includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card sombra-card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">➕ Agregar Nuevo Animal</h4>
                </div>
                <div class="card-body">
                    <form action="../processes/animal_process.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="agregar">
                        <input type="hidden" name="refugio_id" value="<?php echo $refugio_id; ?>">

                        <!-- Información básica -->
                        <h5 class="text-success mb-3">Información Básica</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre del animal *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo *</label>
                                <select class="form-select" name="tipo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="perro">Perro</option>
                                    <option value="gato">Gato</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Edad *</label>
                                <select class="form-select" name="edad_categoria" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="cachorro">Cachorro</option>
                                    <option value="joven">Joven</option>
                                    <option value="adulto">Adulto</option>
                                    <option value="mayor">Mayor</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sexo *</label>
                                <select class="form-select" name="sexo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="macho">Macho</option>
                                    <option value="hembra">Hembra</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tamaño *</label>
                                <select class="form-select" name="tamano" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="pequeno">Pequeño</option>
                                    <option value="mediano">Mediano</option>
                                    <option value="grande">Grande</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Raza</label>
                                <input type="text" class="form-control" name="raza" placeholder="Ej: Mestizo, Labrador, etc.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control" name="peso" step="0.1" min="0">
                            </div>
                        </div>

                        <!-- Salud -->
                        <h5 class="text-success mb-3 mt-4">Salud</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vacunado" value="1">
                                    <label class="form-check-label">¿Está vacunado?</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="esterilizado" value="1">
                                    <label class="form-check-label">¿Está esterilizado?</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vacunas aplicadas</label>
                            <input type="text" class="form-control" name="vacunas" 
                                   placeholder="Ej: Rabia, Polivalente, Moquillo...">
                        </div>

                        <!-- Personalidad -->
                        <h5 class="text-success mb-3 mt-4">Personalidad</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nivel de energía *</label>
                                <select class="form-select" name="nivel_energia" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="bajo">Bajo</option>
                                    <option value="medio">Medio</option>
                                    <option value="alto">Alto</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Relación con niños *</label>
                                <select class="form-select" name="relacion_ninos" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="excelente">Excelente</option>
                                    <option value="buena">Buena</option>
                                    <option value="regular">Regular</option>
                                    <option value="mala">Mala</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Relación con otros animales *</label>
                                <select class="form-select" name="relacion_otros_animales" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="excelente">Excelente</option>
                                    <option value="buena">Buena</option>
                                    <option value="regular">Regular</option>
                                    <option value="mala">Mala</option>
                                </select>
                            </div>
                        </div>

                        <!-- Descripción y necesidades -->
                        <div class="mb-3">
                            <label class="form-label">Descripción *</label>
                            <textarea class="form-control" name="descripcion" rows="4" 
                                      placeholder="Describe al animal, su personalidad, historia, etc..." 
                                      required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Necesidades especiales</label>
                            <textarea class="form-control" name="necesidades_especiales" rows="3" 
                                      placeholder="Alergias, medicación, cuidados especiales..."></textarea>
                        </div>

                        <!-- Fotos -->
                        <h5 class="text-success mb-3 mt-4">Fotos</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Subir fotos del animal</label>
                            <input type="file" class="form-control" name="fotos[]" multiple 
                                   accept="image/*">
                            <div class="form-text">Puedes seleccionar múltiples fotos. La primera será la principal.</div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Guardar Animal</button>
                            <a href="animals.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>