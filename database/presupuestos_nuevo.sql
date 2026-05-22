CREATE DATABASE IF NOT EXISTS presupuestos_nuevo
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE presupuestos_nuevo;

-- =========================
-- TABLAS GENERALES
-- =========================

CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE direcciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    calle_y_numero VARCHAR(255),
    colonia VARCHAR(255),
    delegacion VARCHAR(255),
    id_estado INT,
    codigo_postal INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_direcciones_estados
    FOREIGN KEY (id_estado) REFERENCES estados(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellido_paterno VARCHAR(255),
    apellido_materno VARCHAR(255),
    telefono_1 VARCHAR(20),
    telefono_2 VARCHAR(20),
    email VARCHAR(255),
    rfc VARCHAR(13),
    id_direccion INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_personas_direcciones
    FOREIGN KEY (id_direccion) REFERENCES direcciones(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_direccion_fiscal INT,
    nombre_o_razon_social VARCHAR(255),
    cuenta_catastral VARCHAR(255),
    uso_suelo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_clientes_personas
    FOREIGN KEY (id_persona) REFERENCES personas(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_clientes_direccion_fiscal
    FOREIGN KEY (id_direccion_fiscal) REFERENCES direcciones(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(100),
    descripcion TEXT,
    id_persona INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_empleados_personas
    FOREIGN KEY (id_persona) REFERENCES personas(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    empresa VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_proveedores_personas
    FOREIGN KEY (id_persona) REFERENCES personas(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE unidades_medida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    abreviatura VARCHAR(20) NOT NULL,
    descripcion VARCHAR(180),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    abreviatura VARCHAR(50),
    descripcion VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE bloques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    orden INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================
-- TABLAS DE OBRA
-- =========================

CREATE TABLE datos_de_obra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT,
    id_direccion INT,
    dimensiones_m2 DECIMAL(10,2),
    num_niveles INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_datos_obra_direccion
    FOREIGN KEY (id_direccion) REFERENCES direcciones(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE obras_iniciadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_datos_de_obra INT,
    encargado_id_empleado INT,
    id_cliente INT,
    fecha_inicio DATE,
    duracion VARCHAR(100),
    precio_por_m2_estimado DECIMAL(12,2),
    total_de_obra_estimado DECIMAL(12,2),
    total_presupuestado DECIMAL(12,2),
    total_por_m2 DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_obras_datos_obra
    FOREIGN KEY (id_datos_de_obra) REFERENCES datos_de_obra(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_obras_empleado
    FOREIGN KEY (encargado_id_empleado) REFERENCES empleados(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_obras_cliente
    FOREIGN KEY (id_cliente) REFERENCES clientes(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE niveles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT,
    descripcion VARCHAR(255),
    m2 DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_niveles_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE obras_proceso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT,
    dias_transcurridos INT,
    porcentaje_avanzado DECIMAL(5,2),
    presupuesto_cubierto DECIMAL(12,2),
    presupuesto_restante DECIMAL(12,2),
    porcentaje_restante DECIMAL(5,2),
    estimacion_de_entrega DATE,
    nivel_actual VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_obras_proceso_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE obras_entregadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT,
    fecha_entrega DATE,
    ingresos_generales DECIMAL(12,2),
    egresos DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_obras_entregadas_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- CATÁLOGOS DE COSTOS
-- =========================

CREATE TABLE materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT,
    marca VARCHAR(255),
    id_unidad_medida INT,
    cantidad_contenida DECIMAL(10,2),
    precio_x_unidad DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_materiales_unidad
    FOREIGN KEY (id_unidad_medida) REFERENCES unidades_medida(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE maquinaria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT,
    id_unidad_medida INT,
    precio_x_unidad DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_maquinaria_unidad
    FOREIGN KEY (id_unidad_medida) REFERENCES unidades_medida(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE mano_obra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    id_unidad_medida INT,
    precio_x_unidad DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_mano_obra_unidad
    FOREIGN KEY (id_unidad_medida) REFERENCES unidades_medida(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- CONCEPTOS
-- =========================

CREATE TABLE conceptos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_area INT,
    descripcion TEXT,
    id_unidad_medida INT,
    p_u DECIMAL(12,2),
    duracion_en_dias INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_conceptos_area
    FOREIGN KEY (id_area) REFERENCES areas(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_conceptos_unidad
    FOREIGN KEY (id_unidad_medida) REFERENCES unidades_medida(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- CONCEPTOS ASIGNADOS A OBRA
-- =========================

CREATE TABLE obra_conceptos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT NOT NULL,
    id_concepto INT NOT NULL,
    id_nivel INT,
    id_bloque INT,
    id_area INT,
    cantidad DECIMAL(10,2) DEFAULT 0.00,
    precio_unitario DECIMAL(12,2) DEFAULT 0.00,
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    porcentaje_iva DECIMAL(5,2) DEFAULT 16.00,
    iva DECIMAL(12,2) DEFAULT 0.00,
    total_final DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_obra_conceptos_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_obra_conceptos_concepto
    FOREIGN KEY (id_concepto) REFERENCES conceptos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_obra_conceptos_nivel
    FOREIGN KEY (id_nivel) REFERENCES niveles(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_obra_conceptos_bloque
    FOREIGN KEY (id_bloque) REFERENCES bloques(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_obra_conceptos_area
    FOREIGN KEY (id_area) REFERENCES areas(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- ASIGNACIÓN DE MATERIALES A UN CONCEPTO DE OBRA
-- =========================

CREATE TABLE asigna_materiales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra_concepto INT NOT NULL,
    id_material INT,
    cantidad DECIMAL(10,2) DEFAULT 0.00,
    precio_unitario DECIMAL(12,2) DEFAULT 0.00,
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    porcentaje_iva DECIMAL(5,2) DEFAULT 16.00,
    iva DECIMAL(12,2) DEFAULT 0.00,
    total_final DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_asigna_materiales_obra_concepto
    FOREIGN KEY (id_obra_concepto) REFERENCES obra_conceptos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_asigna_materiales_material
    FOREIGN KEY (id_material) REFERENCES materiales(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- ASIGNACIÓN DE MAQUINARIA A UN CONCEPTO DE OBRA
-- =========================

CREATE TABLE asigna_maquinaria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra_concepto INT NOT NULL,
    id_maquinaria INT,
    cantidad DECIMAL(10,2) DEFAULT 0.00,
    precio_unitario DECIMAL(12,2) DEFAULT 0.00,
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    porcentaje_iva DECIMAL(5,2) DEFAULT 16.00,
    iva DECIMAL(12,2) DEFAULT 0.00,
    total_final DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_asigna_maquinaria_obra_concepto
    FOREIGN KEY (id_obra_concepto) REFERENCES obra_conceptos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_asigna_maquinaria_maquinaria
    FOREIGN KEY (id_maquinaria) REFERENCES maquinaria(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- ASIGNACIÓN DE MANO DE OBRA A UN CONCEPTO DE OBRA
-- =========================

CREATE TABLE asigna_mano_obra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra_concepto INT NOT NULL,
    id_mano_obra INT,
    cantidad DECIMAL(10,2) DEFAULT 0.00,
    precio_unitario DECIMAL(12,2) DEFAULT 0.00,
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    porcentaje_iva DECIMAL(5,2) DEFAULT 16.00,
    iva DECIMAL(12,2) DEFAULT 0.00,
    total_final DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_asigna_mano_obra_obra_concepto
    FOREIGN KEY (id_obra_concepto) REFERENCES obra_conceptos(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_asigna_mano_obra_mano_obra
    FOREIGN KEY (id_mano_obra) REFERENCES mano_obra(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- TOTALES
-- =========================

CREATE TABLE total_bloque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_bloque INT,
    id_obra INT,
    total DECIMAL(12,2),
    iva DECIMAL(12,2),
    total_final DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_total_bloque_bloque
    FOREIGN KEY (id_bloque) REFERENCES bloques(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_total_bloque_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE total_obra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT,
    total_inicial DECIMAL(12,2),
    total_iva DECIMAL(12,2),
    total_final DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_total_obra_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =========================
-- INGRESOS, EGRESOS Y CAJA
-- =========================

CREATE TABLE ingresos_totales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    concepto TEXT,
    id_empleado INT,
    id_obra INT,
    fecha DATE,
    id_total_obra INT,
    monto_dado DECIMAL(12,2),
    saldo_cubierto DECIMAL(12,2),
    porcentaje_cubierto DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_ingresos_empleado
    FOREIGN KEY (id_empleado) REFERENCES empleados(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_ingresos_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_ingresos_total_obra
    FOREIGN KEY (id_total_obra) REFERENCES total_obra(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE egresos_totales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_area INT,
    id_persona INT,
    fecha DATE,
    concepto TEXT,
    pago DECIMAL(12,2),
    id_obra INT,
    categoria VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_egresos_area
    FOREIGN KEY (id_area) REFERENCES areas(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_egresos_persona
    FOREIGN KEY (id_persona) REFERENCES personas(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_egresos_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE caja_general (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_obra INT,
    ingresos_totales DECIMAL(12,2),
    egresos_totales DECIMAL(12,2),
    saldo DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_caja_general_obra
    FOREIGN KEY (id_obra) REFERENCES obras_iniciadas(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
