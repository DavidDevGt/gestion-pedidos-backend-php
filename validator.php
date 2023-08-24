<?php
// interfaz para todas las clases con el método validate
interface Validatable
{
    public function validate(array &$data): void;
}

// Clase Validator
class Validator implements Validatable
{
    private $rules; // Guardar las reglas

    // Al crear nuevo objeto se le aplican las reglas
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    // Verificacion
    public function validate(array &$data): void
    {
        // validar todo
        foreach ($this->rules as $field => $rule) {
            if (!isset($data[$field]) || !$rule($data[$field])) {
                throw new Exception("$field Inválido");
            }
            // Limpiar
            $data[$field] = filter_var($data[$field], FILTER_SANITIZE_STRING);
        }
    }
}

// Definir las reglas datos del cliente.
$clienteRules = [
    'nombre' => fn($val) => preg_match('/^[a-zA-Z\s]+$/', $val),
    'direccion' => fn($val) => true, // No se realiza
    'telefono' => fn($val) => preg_match('/^\d{7,15}$/', $val)
];

// Definir las reglas datos del vendedor.
$vendedorRules = [
    'nombre' => fn($val) => preg_match('/^[a-zA-Z\s]+$/', $val),
    'email' => fn($val) => filter_var($val, FILTER_VALIDATE_EMAIL)
];

// Definir las reglas datos del pedido.
$pedidoRules = [
    'vendedor_id' => fn($val) => filter_var($val, FILTER_VALIDATE_INT),
    'cliente_id' => fn($val) => filter_var($val, FILTER_VALIDATE_INT),
    'estado' => fn($val) => in_array($val, ['pendiente', 'enviado', 'entregado']),
    'detalles' => fn($val) => true // No se realiza
];

// Hacer q cada tipo de dato use las reglas
$clienteValidator = new Validator($clienteRules);
$vendedorValidator = new Validator($vendedorRules);
$pedidoValidator = new Validator($pedidoRules);

try {
    $clienteValidator->validate($clienteData);
    $vendedorValidator->validate($vendedorData);
    $pedidoValidator->validate($pedidoData);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage(); // Mostrar un mensaje de error
}
?>
