-- Primero agregamos la columna si no existe
ALTER TABLE habitaciones ADD COLUMN IF NOT EXISTS fotografia VARCHAR(255);

-- Actualizamos las habitaciones con sus imágenes existentes
UPDATE habitaciones SET fotografia = 'Suite 2 personas.jpg' WHERE nombre_tipo LIKE '%Suite%';
UPDATE habitaciones SET fotografia = '6848e161cb2e9.jpg' WHERE nombre_tipo LIKE '%Doble%';
UPDATE habitaciones SET fotografia = '6848f753e15e0.jpg' WHERE nombre_tipo LIKE '%Individual%';
UPDATE habitaciones SET fotografia = '6848f766a724b.jpg' WHERE nombre_tipo LIKE '%Familiar%';
UPDATE habitaciones SET fotografia = 'CuartoPersonal.jpg' WHERE nombre_tipo LIKE '%Personal%';

-- También podemos agregar una columna para múltiples imágenes si se necesita
ALTER TABLE habitaciones ADD COLUMN IF NOT EXISTS imagenes TEXT;

-- Actualizar con múltiples imágenes (separadas por comas)
UPDATE habitaciones SET imagenes = 'Suite 2 personas.jpg,6848e161cb2e9.jpg' WHERE nombre_tipo LIKE '%Suite%';
UPDATE habitaciones SET imagenes = '6848e161cb2e9.jpg,6848f753e15e0.jpg' WHERE nombre_tipo LIKE '%Doble%';
UPDATE habitaciones SET imagenes = '6848f753e15e0.jpg,6848f766a724b.jpg' WHERE nombre_tipo LIKE '%Individual%';
UPDATE habitaciones SET imagenes = '6848f766a724b.jpg,CuartoPersonal.jpg' WHERE nombre_tipo LIKE '%Familiar%';
UPDATE habitaciones SET imagenes = 'CuartoPersonal.jpg,Suite 2 personas.jpg' WHERE nombre_tipo LIKE '%Personal%'; 