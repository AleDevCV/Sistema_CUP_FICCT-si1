-- =============================================================================
-- Script de Base de Datos - Sistema CUP (Admisión Universitaria)
-- PostgreSQL
-- =============================================================================

BEGIN;


DROP TYPE IF EXISTS sexo_postulante CASCADE;
DROP TYPE IF EXISTS estado_final_postulante CASCADE;
DROP TYPE IF EXISTS metodo_pago CASCADE;
DROP TYPE IF EXISTS estado_pago CASCADE;

CREATE TYPE sexo_postulante AS ENUM ('Masculino', 'Femenino');
CREATE TYPE estado_final_postulante AS ENUM ('APROBADO', 'REPROBADO', 'PENDIENTE');
CREATE TYPE metodo_pago AS ENUM ('Transferencia', 'QR', 'Tarjeta', 'Efectivo');
CREATE TYPE estado_pago AS ENUM ('PENDIENTE', 'PAGADO', 'RECHAZADO');

-- -----------------------------------------------------------------------------
-- Eliminar tablas existentes (orden inverso por dependencias de FK)
-- -----------------------------------------------------------------------------

DROP TABLE IF EXISTS grupo_docentes CASCADE;
DROP TABLE IF EXISTS examenes CASCADE;
DROP TABLE IF EXISTS pagos CASCADE;
DROP TABLE IF EXISTS postulantes CASCADE;
DROP TABLE IF EXISTS docentes CASCADE;
DROP TABLE IF EXISTS personal_access_tokens CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS roles CASCADE;
DROP TABLE IF EXISTS grupos CASCADE;
DROP TABLE IF EXISTS materias CASCADE;
DROP TABLE IF EXISTS carreras CASCADE;

-- -----------------------------------------------------------------------------
-- 1. roles
-- -----------------------------------------------------------------------------

CREATE TABLE roles (
    id          BIGSERIAL PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at  TIMESTAMP,
    updated_at  TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- 2. users
-- -----------------------------------------------------------------------------

CREATE TABLE users (
    id                BIGSERIAL PRIMARY KEY,
    role_id           BIGINT       NOT NULL,
    name              VARCHAR(255) NOT NULL,
    username          VARCHAR(50)  NOT NULL UNIQUE,
    email             VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP,
    password          VARCHAR(255) NOT NULL,
    status            BOOLEAN      NOT NULL DEFAULT TRUE,
    remember_token    VARCHAR(100),
    created_at        TIMESTAMP,
    updated_at        TIMESTAMP,

    CONSTRAINT users_role_id_foreign
        FOREIGN KEY (role_id) REFERENCES roles (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE INDEX users_role_id_index ON users (role_id);

-- -----------------------------------------------------------------------------
-- 3. carreras
-- -----------------------------------------------------------------------------

CREATE TABLE carreras (
    id         BIGSERIAL PRIMARY KEY,
    codigo     VARCHAR(20)  NOT NULL UNIQUE,
    nombre     VARCHAR(100) NOT NULL,
    cupo       INTEGER      NOT NULL,
    gestion    SMALLINT     NOT NULL,
    estado     BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- 4. materias
-- -----------------------------------------------------------------------------

CREATE TABLE materias (
    id          BIGSERIAL PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado      BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at  TIMESTAMP,
    updated_at  TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- 5. grupos
-- -----------------------------------------------------------------------------

CREATE TABLE grupos (
    id               BIGSERIAL PRIMARY KEY,
    nombre           VARCHAR(50)  NOT NULL,
    codigo           VARCHAR(20)  NOT NULL UNIQUE,
    aula             VARCHAR(50),
    horario          VARCHAR(100),
    capacidad_maxima INTEGER      NOT NULL DEFAULT 70,
    estado           BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- 6. postulantes
-- -----------------------------------------------------------------------------

CREATE TABLE postulantes (
    id                         BIGSERIAL PRIMARY KEY,
    ci                         VARCHAR(20)  NOT NULL UNIQUE,
    nombres                    VARCHAR(100) NOT NULL,
    apellidos                  VARCHAR(100) NOT NULL,
    fecha_nacimiento           DATE         NOT NULL,
    sexo                       sexo_postulante NOT NULL,
    direccion                  VARCHAR(255) NOT NULL,
    telefono                   VARCHAR(20)  NOT NULL,
    email                      VARCHAR(255) NOT NULL UNIQUE,
    colegio                    VARCHAR(255) NOT NULL,
    ciudad                     VARCHAR(255) NOT NULL,
    titulo_bachiller           VARCHAR(255) NOT NULL,
    otros_requisitos           TEXT,
    carrera_primera_opcion_id  BIGINT       NOT NULL,
    carrera_segunda_opcion_id  BIGINT,
    grupo_id                   BIGINT,
    promedio_final             DECIMAL(5, 2) NOT NULL DEFAULT 0,
    estado_final               estado_final_postulante NOT NULL DEFAULT 'PENDIENTE',
    estado                     BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at                 TIMESTAMP,
    updated_at                 TIMESTAMP,

    CONSTRAINT postulantes_carrera_primera_opcion_id_foreign
        FOREIGN KEY (carrera_primera_opcion_id) REFERENCES carreras (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT postulantes_carrera_segunda_opcion_id_foreign
        FOREIGN KEY (carrera_segunda_opcion_id) REFERENCES carreras (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT postulantes_grupo_id_foreign
        FOREIGN KEY (grupo_id) REFERENCES grupos (id)
        ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE INDEX postulantes_carrera_primera_opcion_id_index ON postulantes (carrera_primera_opcion_id);
CREATE INDEX postulantes_carrera_segunda_opcion_id_index ON postulantes (carrera_segunda_opcion_id);
CREATE INDEX postulantes_grupo_id_index ON postulantes (grupo_id);

-- -----------------------------------------------------------------------------
-- 7. docentes
-- -----------------------------------------------------------------------------

CREATE TABLE docentes (
    id                            BIGSERIAL PRIMARY KEY,
    user_id                       BIGINT       NOT NULL UNIQUE,
    ci                            VARCHAR(20)  NOT NULL UNIQUE,
    nombres                       VARCHAR(100) NOT NULL,
    apellidos                     VARCHAR(100) NOT NULL,
    telefono                      VARCHAR(20),
    email                         VARCHAR(255) NOT NULL UNIQUE,
    profesion                     VARCHAR(255) NOT NULL,
    maestria                      BOOLEAN      NOT NULL DEFAULT FALSE,
    diplomado_educacion_superior  BOOLEAN      NOT NULL DEFAULT FALSE,
    contratado                    BOOLEAN      NOT NULL DEFAULT FALSE,
    estado                        BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at                    TIMESTAMP,
    updated_at                    TIMESTAMP,

    CONSTRAINT docentes_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- -----------------------------------------------------------------------------
-- 8. pagos
-- -----------------------------------------------------------------------------

CREATE TABLE pagos (
    id                 BIGSERIAL PRIMARY KEY,
    postulante_id      BIGINT        NOT NULL UNIQUE,
    monto              DECIMAL(10, 2) NOT NULL,
    metodo_pago        metodo_pago   NOT NULL,
    codigo_transaccion VARCHAR(255),
    estado             estado_pago   NOT NULL DEFAULT 'PENDIENTE',
    fecha_pago         TIMESTAMP,
    created_at         TIMESTAMP,
    updated_at         TIMESTAMP,

    CONSTRAINT pagos_postulante_id_foreign
        FOREIGN KEY (postulante_id) REFERENCES postulantes (id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- -----------------------------------------------------------------------------
-- 9. examenes
-- -----------------------------------------------------------------------------

CREATE TABLE examenes (
    id             BIGSERIAL PRIMARY KEY,
    postulante_id  BIGINT        NOT NULL,
    materia_id     BIGINT        NOT NULL,
    numero_examen  SMALLINT      NOT NULL,
    nota           DECIMAL(5, 2) NOT NULL,
    porcentaje     DECIMAL(5, 2) NOT NULL,
    created_at     TIMESTAMP,
    updated_at     TIMESTAMP,

    CONSTRAINT examenes_postulante_id_foreign
        FOREIGN KEY (postulante_id) REFERENCES postulantes (id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT examenes_materia_id_foreign
        FOREIGN KEY (materia_id) REFERENCES materias (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT examenes_postulante_materia_numero_unique
        UNIQUE (postulante_id, materia_id, numero_examen)
);

CREATE INDEX examenes_postulante_id_index ON examenes (postulante_id);
CREATE INDEX examenes_materia_id_index ON examenes (materia_id);

-- -----------------------------------------------------------------------------
-- 10. grupo_docentes (tabla pivote: docente + grupo + materia)
-- -----------------------------------------------------------------------------

CREATE TABLE grupo_docentes (
    id         BIGSERIAL PRIMARY KEY,
    docente_id BIGINT NOT NULL,
    grupo_id   BIGINT NOT NULL,
    materia_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    CONSTRAINT grupo_docentes_docente_id_foreign
        FOREIGN KEY (docente_id) REFERENCES docentes (id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT grupo_docentes_grupo_id_foreign
        FOREIGN KEY (grupo_id) REFERENCES grupos (id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT grupo_docentes_materia_id_foreign
        FOREIGN KEY (materia_id) REFERENCES materias (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT grupo_docentes_docente_grupo_materia_unique
        UNIQUE (docente_id, grupo_id, materia_id)
);

CREATE INDEX grupo_docentes_docente_id_index ON grupo_docentes (docente_id);
CREATE INDEX grupo_docentes_grupo_id_index ON grupo_docentes (grupo_id);
CREATE INDEX grupo_docentes_materia_id_index ON grupo_docentes (materia_id);

-- -----------------------------------------------------------------------------
-- Tablas del framework Laravel
-- -----------------------------------------------------------------------------

-- password_reset_tokens
CREATE TABLE password_reset_tokens (
    email      VARCHAR(255) PRIMARY KEY,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP
);

-- failed_jobs
CREATE TABLE failed_jobs (
    id         BIGSERIAL PRIMARY KEY,
    uuid       VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT         NOT NULL,
    queue      TEXT         NOT NULL,
    payload    TEXT         NOT NULL,
    exception  TEXT         NOT NULL,
    failed_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- personal_access_tokens (Sanctum)
CREATE TABLE personal_access_tokens (
    id             BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id   BIGINT       NOT NULL,
    name           VARCHAR(255) NOT NULL,
    token          VARCHAR(64)  NOT NULL UNIQUE,
    abilities      TEXT,
    last_used_at   TIMESTAMP,
    expires_at     TIMESTAMP,
    created_at     TIMESTAMP,
    updated_at     TIMESTAMP
);

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index
    ON personal_access_tokens (tokenable_type, tokenable_id);

COMMIT;

-- =============================================================================
-- DATOS DE PRUEBA (INSERT / UPDATE / DELETE)
-- Ejecutar después de crear las tablas (schema anterior).
-- =============================================================================

BEGIN;

-- -----------------------------------------------------------------------------
-- INSERT()
-- Orden: roles → users → carreras → materias → grupos → postulantes
--        → docentes → pagos → examenes → grupo_docentes
-- -----------------------------------------------------------------------------

-- roles (requerido por users)
INSERT INTO roles (id, name, description, created_at, updated_at) VALUES
(1, 'Administrador', 'Acceso total al sistema',              NOW(), NOW()),
(2, 'Docente',       'Gestión de exámenes y grupos',         NOW(), NOW()),
(3, 'Postulante',    'Registro y consulta de resultados',    NOW(), NOW());

-- users
INSERT INTO users (id, role_id, name, username, email, password, status, created_at, updated_at) VALUES
(1, 1, 'Carlos Mendoza',    'cmendoza',  'admin@cupsystem.edu.bo',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE, NOW(), NOW()),
(2, 2, 'Ana Rodríguez',     'arodriguez','ana.rodriguez@cupsystem.edu.bo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE, NOW(), NOW()),
(3, 2, 'Luis Fernández',    'lfernandez','luis.fernandez@cupsystem.edu.bo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE, NOW(), NOW()),
(4, 2, 'María López',       'mlopez',    'maria.lopez@cupsystem.edu.bo',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE, NOW(), NOW()),
(5, 2, 'Pedro Sánchez',     'psanchez',  'pedro.sanchez@cupsystem.edu.bo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW(), NOW());

-- carreras
INSERT INTO carreras (id, codigo, nombre, cupo, gestion, estado, created_at, updated_at) VALUES
(1, 'ING-SIS', 'Ingeniería de Sistemas',           40, 2026, TRUE, NOW(), NOW()),
(2, 'MED-GEN', 'Medicina General',                 30, 2026, TRUE, NOW(), NOW()),
(3, 'ADM-EMP', 'Administración de Empresas',       50, 2026, TRUE, NOW(), NOW()),
(4, 'DER-NOT', 'Derecho Notarial',                 35, 2026, TRUE, NOW(), NOW()),
(5, 'ING-CIV', 'Ingeniería Civil',                 25, 2026, TRUE, NOW(), NOW());

-- materias
INSERT INTO materias (id, nombre, descripcion, estado, created_at, updated_at) VALUES
(1, 'Matemáticas',     'Álgebra, geometría y razonamiento lógico',     TRUE, NOW(), NOW()),
(2, 'Lenguaje',        'Comprensión lectora y expresión escrita',      TRUE, NOW(), NOW()),
(3, 'Ciencias',        'Biología, química y física básica',            TRUE, NOW(), NOW()),
(4, 'Conocimientos Generales', 'Historia, geografía y actualidad',     TRUE, NOW(), NOW());

-- grupos
INSERT INTO grupos (id, nombre, codigo, aula, horario, capacidad_maxima, estado, created_at, updated_at) VALUES
(1, 'Grupo A', 'GRP-A-2026', 'Aula 101', 'Lunes y Miércoles 08:00-12:00', 70, TRUE, NOW(), NOW()),
(2, 'Grupo B', 'GRP-B-2026', 'Aula 102', 'Martes y Jueves 14:00-18:00',   70, TRUE, NOW(), NOW()),
(3, 'Grupo C', 'GRP-C-2026', 'Aula 103', 'Viernes 08:00-16:00',           50, TRUE, NOW(), NOW());

-- postulantes
INSERT INTO postulantes (
    id, ci, nombres, apellidos, fecha_nacimiento, sexo, direccion, telefono, email,
    colegio, ciudad, titulo_bachiller, otros_requisitos,
    carrera_primera_opcion_id, carrera_segunda_opcion_id, grupo_id,
    promedio_final, estado_final, estado, created_at, updated_at
) VALUES
(1, '7890123', 'Juan',     'Pérez',    '2005-03-15', 'Masculino', 'Av. Busch 123',       '71234567', 'juan.perez@email.com',      'Colegio San Ignacio',   'La Paz',   'Bachiller en Humanidades', NULL,                   1, 3, 1, 78.50, 'APROBADO',  TRUE, NOW(), NOW()),
(2, '7890124', 'Laura',    'Gutiérrez','2004-07-22', 'Femenino',  'Calle Comercio 456',  '72345678', 'laura.gutierrez@email.com', 'Colegio La Salle',      'El Alto',  'Bachiller en Ciencias',    'Certificado idioma inglés', 2, 1, 1, 65.25, 'REPROBADO', TRUE, NOW(), NOW()),
(3, '7890125', 'Roberto',  'Vargas',   '2005-11-08', 'Masculino', 'Zona Villa Fátima',   '73456789', 'roberto.vargas@email.com',  'Colegio Nacional',      'La Paz',   'Bachiller en Humanidades', NULL,                   3, 4, 2, 82.00, 'APROBADO',  TRUE, NOW(), NOW()),
(4, '7890126', 'Sofía',    'Ramos',    '2004-01-30', 'Femenino',  'Av. 6 de Agosto 789', '74567890', 'sofia.ramos@email.com',     'Colegio Don Bosco',     'La Paz',   'Bachiller en Ciencias',    NULL,                   1, 5, 2, 55.75, 'REPROBADO', TRUE, NOW(), NOW()),
(5, '7890127', 'Diego',    'Condori',  '2005-09-12', 'Masculino', 'Zona Miraflores',     '75678901', 'diego.condori@email.com',   'Colegio San Calixto',   'La Paz',   'Bachiller en Humanidades', 'Carta de motivación',  4, 3, 2, 71.00, 'PENDIENTE', TRUE, NOW(), NOW()),
(6, '7890128', 'Valeria',  'Mamani',   '2005-05-20', 'Femenino',  'Zona Sopocachi',      '76789012', 'valeria.mamani@email.com',  'Colegio Franco Boliviano','La Paz', 'Bachiller en Ciencias',    NULL,                   2, 1, 3,  0.00, 'PENDIENTE', TRUE, NOW(), NOW());

-- docentes
INSERT INTO docentes (
    id, user_id, ci, nombres, apellidos, telefono, email,
    profesion, maestria, diplomado_educacion_superior, contratado, estado, created_at, updated_at
) VALUES
(1, 2, '4567890', 'Ana',   'Rodríguez', '70111222', 'ana.rodriguez@cupsystem.edu.bo',   'Licenciada en Matemáticas',     TRUE,  TRUE,  TRUE,  TRUE, NOW(), NOW()),
(2, 3, '4567891', 'Luis',  'Fernández', '70222333', 'luis.fernandez@cupsystem.edu.bo',  'Licenciado en Lengua y Literatura', FALSE, TRUE, TRUE, TRUE, NOW(), NOW()),
(3, 4, '4567892', 'María', 'López',     '70333444', 'maria.lopez@cupsystem.edu.bo',     'Licenciada en Biología',        TRUE,  TRUE,  TRUE,  TRUE, NOW(), NOW()),
(4, 5, '4567893', 'Pedro', 'Sánchez',   '70444555', 'pedro.sanchez@cupsystem.edu.bo',   'Licenciado en Historia',        FALSE, FALSE, FALSE, FALSE, NOW(), NOW());

-- pagos (relación 1:1 con postulante)
INSERT INTO pagos (id, postulante_id, monto, metodo_pago, codigo_transaccion, estado, fecha_pago, created_at, updated_at) VALUES
(1, 1, 150.00, 'Transferencia', 'TRX-2026-0001', 'PAGADO',    '2026-01-10 09:30:00', NOW(), NOW()),
(2, 2, 150.00, 'QR',            'TRX-2026-0002', 'PAGADO',    '2026-01-11 11:15:00', NOW(), NOW()),
(3, 3, 150.00, 'Tarjeta',       'TRX-2026-0003', 'PAGADO',    '2026-01-12 16:45:00', NOW(), NOW()),
(4, 4, 150.00, 'Efectivo',      'TRX-2026-0004', 'PAGADO',    '2026-01-13 10:00:00', NOW(), NOW()),
(5, 5, 150.00, 'Transferencia', NULL,            'PENDIENTE', NULL,                  NOW(), NOW()),
(6, 6, 150.00, 'QR',            'TRX-2026-0006', 'RECHAZADO', NULL,                  NOW(), NOW());

-- examenes
INSERT INTO examenes (id, postulante_id, materia_id, numero_examen, nota, porcentaje, created_at, updated_at) VALUES
(1,  1, 1, 1, 85.00, 30.00, NOW(), NOW()),
(2,  1, 2, 1, 72.00, 25.00, NOW(), NOW()),
(3,  1, 3, 1, 78.50, 25.00, NOW(), NOW()),
(4,  1, 4, 1, 80.00, 20.00, NOW(), NOW()),
(5,  2, 1, 1, 55.00, 30.00, NOW(), NOW()),
(6,  2, 2, 1, 60.00, 25.00, NOW(), NOW()),
(7,  2, 3, 1, 70.00, 25.00, NOW(), NOW()),
(8,  2, 4, 1, 76.00, 20.00, NOW(), NOW()),
(9,  3, 1, 1, 90.00, 30.00, NOW(), NOW()),
(10, 3, 2, 1, 88.00, 25.00, NOW(), NOW()),
(11, 3, 3, 1, 75.00, 25.00, NOW(), NOW()),
(12, 3, 4, 1, 75.00, 20.00, NOW(), NOW()),
(13, 4, 1, 1, 45.00, 30.00, NOW(), NOW()),
(14, 4, 2, 1, 50.00, 25.00, NOW(), NOW()),
(15, 4, 3, 1, 62.00, 25.00, NOW(), NOW()),
(16, 4, 4, 1, 66.00, 20.00, NOW(), NOW()),
(17, 5, 1, 1, 68.00, 30.00, NOW(), NOW()),
(18, 5, 2, 1, 74.00, 25.00, NOW(), NOW());

-- grupo_docentes
INSERT INTO grupo_docentes (id, docente_id, grupo_id, materia_id, created_at, updated_at) VALUES
(1, 1, 1, 1, NOW(), NOW()),
(2, 2, 1, 2, NOW(), NOW()),
(3, 3, 1, 3, NOW(), NOW()),
(4, 1, 2, 1, NOW(), NOW()),
(5, 2, 2, 2, NOW(), NOW()),
(6, 3, 2, 3, NOW(), NOW()),
(7, 1, 3, 1, NOW(), NOW()),
(8, 2, 3, 4, NOW(), NOW());

-- Ajustar secuencias después de IDs explícitos
SELECT setval('roles_id_seq',            (SELECT MAX(id) FROM roles));
SELECT setval('users_id_seq',            (SELECT MAX(id) FROM users));
SELECT setval('carreras_id_seq',         (SELECT MAX(id) FROM carreras));
SELECT setval('materias_id_seq',         (SELECT MAX(id) FROM materias));
SELECT setval('grupos_id_seq',           (SELECT MAX(id) FROM grupos));
SELECT setval('postulantes_id_seq',      (SELECT MAX(id) FROM postulantes));
SELECT setval('docentes_id_seq',         (SELECT MAX(id) FROM docentes));
SELECT setval('pagos_id_seq',            (SELECT MAX(id) FROM pagos));
SELECT setval('examenes_id_seq',         (SELECT MAX(id) FROM examenes));
SELECT setval('grupo_docentes_id_seq',   (SELECT MAX(id) FROM grupo_docentes));

COMMIT;

-- -----------------------------------------------------------------------------
-- UPDATE() — Ejemplos de actualización
-- -----------------------------------------------------------------------------

-- 1. Aprobar postulante tras revisión de notas
UPDATE postulantes
SET promedio_final = 73.50,
    estado_final   = 'APROBADO',
    updated_at     = NOW()
WHERE ci = '7890127';

-- 2. Confirmar pago pendiente de inscripción
UPDATE pagos
SET estado             = 'PAGADO',
    codigo_transaccion = 'TRX-2026-0005-CONF',
    fecha_pago         = NOW(),
    updated_at         = NOW()
WHERE postulante_id = (SELECT id FROM postulantes WHERE ci = '7890127');

-- 3. Ampliar cupo de una carrera para la gestión 2026
UPDATE carreras
SET cupo       = 45,
    updated_at = NOW()
WHERE codigo = 'ING-SIS' AND gestion = 2026;

-- 4. Contratar docente y activar su usuario
UPDATE docentes
SET contratado = TRUE,
    estado     = TRUE,
    updated_at = NOW()
WHERE ci = '4567893';

UPDATE users
SET status     = TRUE,
    updated_at = NOW()
WHERE username = 'psanchez';

-- 5. Corregir nota de un examen y recalcular promedio del postulante
UPDATE examenes
SET nota       = 58.00,
    updated_at = NOW()
WHERE postulante_id = (SELECT id FROM postulantes WHERE ci = '7890124')
  AND materia_id    = 1
  AND numero_examen = 1;

UPDATE postulantes
SET promedio_final = (
        SELECT ROUND(AVG(nota), 2)
        FROM examenes
        WHERE postulante_id = postulantes.id
    ),
    updated_at = NOW()
WHERE ci = '7890124';

-- -----------------------------------------------------------------------------
-- DELETE() — Ejemplos de eliminación
-- -----------------------------------------------------------------------------

-- 1. Eliminar un registro de examen específico (sin afectar al postulante)
DELETE FROM examenes
WHERE postulante_id = (SELECT id FROM postulantes WHERE ci = '7890127')
  AND materia_id    = 2
  AND numero_examen = 1;

-- 2. Quitar asignación docente-grupo-materia
DELETE FROM grupo_docentes
WHERE docente_id = (SELECT id FROM docentes WHERE ci = '4567893')
  AND grupo_id   = 3
  AND materia_id = 4;

-- 3. Eliminar pago rechazado antes de re-registrar al postulante
DELETE FROM pagos
WHERE postulante_id = (SELECT id FROM postulantes WHERE ci = '7890128')
  AND estado        = 'RECHAZADO';

-- 4. Eliminar postulante sin exámenes registrados (cascade elimina pagos asociados)
DELETE FROM postulantes
WHERE ci = '7890128';

-- 5. Eliminar grupo sin postulantes (primero quitar asignaciones en grupo_docentes)
DELETE FROM grupo_docentes
WHERE grupo_id = (SELECT id FROM grupos WHERE codigo = 'GRP-C-2026');

DELETE FROM grupos
WHERE codigo = 'GRP-C-2026'
  AND NOT EXISTS (SELECT 1 FROM postulantes WHERE grupo_id = grupos.id);



-- =============================================================================
-- CONSULTAS SIMPLES (10)
-- =============================================================================

-- 1. Listar todas las carreras activas
SELECT id, codigo, nombre, cupo, gestion
FROM carreras
WHERE estado = TRUE
ORDER BY nombre;

-- 2. Contar postulantes por estado final
SELECT estado_final, COUNT(*) AS total
FROM postulantes
GROUP BY estado_final
ORDER BY total DESC;

-- 3. Obtener datos de un postulante por CI
SELECT ci, nombres, apellidos, email, promedio_final, estado_final
FROM postulantes
WHERE ci = '7890123';

-- 4. Listar materias habilitadas
SELECT id, nombre, descripcion
FROM materias
WHERE estado = TRUE;

-- 5. Pagos pendientes de confirmación
SELECT id, postulante_id, monto, metodo_pago, estado
FROM pagos
WHERE estado = 'PENDIENTE';

-- 6. Docentes contratados con maestría
SELECT ci, nombres, apellidos, profesion, email
FROM docentes
WHERE contratado = TRUE AND maestria = TRUE;

-- 7. Grupos con su capacidad máxima
SELECT nombre, codigo, aula, horario, capacidad_maxima
FROM grupos
WHERE estado = TRUE
ORDER BY codigo;

-- 8. Promedio de notas por materia
SELECT materia_id, ROUND(AVG(nota), 2) AS promedio_nota, COUNT(*) AS total_examenes
FROM examenes
GROUP BY materia_id
ORDER BY materia_id;

-- 9. Usuarios activos del sistema
SELECT username, name, email, status
FROM users
WHERE status = TRUE;

-- 10. Postulantes de la ciudad de La Paz
SELECT ci, nombres, apellidos, colegio, promedio_final
FROM postulantes
WHERE ciudad = 'La Paz'
ORDER BY apellidos;


-- =============================================================================
-- CONSULTAS MULTIPLES / JOIN (10)
-- =============================================================================

-- 1. Postulantes con nombre de su carrera de primera opción
SELECT p.ci,
       p.nombres || ' ' || p.apellidos AS postulante,
       c.codigo AS carrera_codigo,
       c.nombre AS carrera_nombre,
       p.promedio_final,
       p.estado_final
FROM postulantes p
INNER JOIN carreras c ON p.carrera_primera_opcion_id = c.id
ORDER BY p.apellidos;

-- 2. Postulantes con grupo y aula asignada
SELECT p.ci,
       p.nombres || ' ' || p.apellidos AS postulante,
       g.codigo AS grupo,
       g.aula,
       g.horario
FROM postulantes p
LEFT JOIN grupos g ON p.grupo_id = g.id
ORDER BY g.codigo, p.apellidos;

-- 3. Pagos con datos del postulante
SELECT pg.id AS pago_id,
       p.ci,
       p.nombres || ' ' || p.apellidos AS postulante,
       pg.monto,
       pg.metodo_pago,
       pg.estado,
       pg.fecha_pago
FROM pagos pg
INNER JOIN postulantes p ON pg.postulante_id = p.id
ORDER BY pg.estado, pg.fecha_pago;

-- 4. Exámenes con postulante y materia
SELECT p.ci,
       p.nombres || ' ' || p.apellidos AS postulante,
       m.nombre AS materia,
       e.numero_examen,
       e.nota,
       e.porcentaje
FROM examenes e
INNER JOIN postulantes p ON e.postulante_id = p.id
INNER JOIN materias m ON e.materia_id = m.id
ORDER BY p.apellidos, m.nombre;

-- 5. Docentes con rol y usuario del sistema
SELECT d.ci,
       d.nombres || ' ' || d.apellidos AS docente,
       d.profesion,
       u.username,
       u.email,
       r.name AS rol
FROM docentes d
INNER JOIN users u ON d.user_id = u.id
INNER JOIN roles r ON u.role_id = r.id
ORDER BY d.apellidos;

-- 6. Asignaciones docente-grupo-materia (vista completa)
SELECT g.codigo AS grupo,
       g.aula,
       d.nombres || ' ' || d.apellidos AS docente,
       m.nombre AS materia
FROM grupo_docentes gd
INNER JOIN docentes d ON gd.docente_id = d.id
INNER JOIN grupos g ON gd.grupo_id = g.id
INNER JOIN materias m ON gd.materia_id = m.id
ORDER BY g.codigo, m.nombre;

-- 7. Postulantes con primera y segunda opción de carrera
SELECT p.ci,
       p.nombres || ' ' || p.apellidos AS postulante,
       c1.nombre AS primera_opcion,
       c2.nombre AS segunda_opcion
FROM postulantes p
INNER JOIN carreras c1 ON p.carrera_primera_opcion_id = c1.id
LEFT JOIN carreras c2 ON p.carrera_segunda_opcion_id = c2.id
ORDER BY p.ci;

-- 8. Cantidad de postulantes por carrera (primera opción)
SELECT c.codigo,
       c.nombre AS carrera,
       COUNT(p.id) AS total_postulantes
FROM carreras c
LEFT JOIN postulantes p ON c.id = p.carrera_primera_opcion_id
GROUP BY c.id, c.codigo, c.nombre
ORDER BY total_postulantes DESC;

-- 9. Nota máxima por materia con nombre del postulante
SELECT m.nombre AS materia,
       e.nota AS nota_maxima,
       p.nombres || ' ' || p.apellidos AS postulante
FROM examenes e
INNER JOIN materias m ON e.materia_id = m.id
INNER JOIN postulantes p ON e.postulante_id = p.id
INNER JOIN (
    SELECT materia_id, MAX(nota) AS nota_max
    FROM examenes
    GROUP BY materia_id
) nm ON e.materia_id = nm.materia_id AND e.nota = nm.nota_max
ORDER BY m.nombre;

-- 10. Usuarios docentes con estado de contratación
SELECT u.username,
       u.name,
       d.contratado,
       d.estado AS docente_activo,
       COUNT(gd.id) AS materias_asignadas
FROM users u
INNER JOIN docentes d ON u.id = d.user_id
LEFT JOIN grupo_docentes gd ON d.id = gd.docente_id
GROUP BY u.id, u.username, u.name, d.contratado, d.estado
ORDER BY u.username;


-- =============================================================================
-- SUBCONSULTAS (10)
-- =============================================================================

-- 1. Postulantes con promedio superior al promedio general
SELECT ci, nombres, apellidos, promedio_final
FROM postulantes
WHERE promedio_final > (SELECT ROUND(AVG(promedio_final), 2) FROM postulantes WHERE promedio_final > 0)
ORDER BY promedio_final DESC;

-- 2. Carreras que tienen al menos un postulante aprobado
SELECT codigo, nombre, cupo
FROM carreras
WHERE id IN (
    SELECT carrera_primera_opcion_id
    FROM postulantes
    WHERE estado_final = 'APROBADO'
);

-- 3. Postulantes que no tienen pago confirmado
SELECT ci, nombres, apellidos, email
FROM postulantes
WHERE id NOT IN (
    SELECT postulante_id FROM pagos WHERE estado = 'PAGADO'
);

-- 4. Docentes sin asignación en ningún grupo
SELECT ci, nombres, apellidos, profesion
FROM docentes
WHERE id NOT IN (SELECT DISTINCT docente_id FROM grupo_docentes);

-- 5. Materias con nota promedio mayor a 70
SELECT nombre
FROM materias
WHERE id IN (
    SELECT materia_id
    FROM examenes
    GROUP BY materia_id
    HAVING AVG(nota) > 70
);

-- 6. Grupos con capacidad disponible (capacidad > postulantes asignados)
SELECT g.codigo, g.nombre, g.capacidad_maxima,
       (SELECT COUNT(*) FROM postulantes p WHERE p.grupo_id = g.id) AS ocupados
FROM grupos g
WHERE g.capacidad_maxima > (
    SELECT COUNT(*) FROM postulantes p WHERE p.grupo_id = g.id
);

-- 7. Postulante(s) con la nota más alta en Matemáticas
SELECT p.ci, p.nombres, p.apellidos, e.nota
FROM postulantes p
INNER JOIN examenes e ON p.id = e.postulante_id
WHERE e.materia_id = (SELECT id FROM materias WHERE nombre = 'Matemáticas')
  AND e.nota = (
      SELECT MAX(nota)
      FROM examenes
      WHERE materia_id = (SELECT id FROM materias WHERE nombre = 'Matemáticas')
  );

-- 8. Carreras con cupo mayor al promedio de cupos de todas las carreras
SELECT codigo, nombre, cupo
FROM carreras
WHERE cupo > (SELECT ROUND(AVG(cupo), 0) FROM carreras)
ORDER BY cupo DESC;

-- 9. Postulantes reprobados con al menos un examen menor a 50
SELECT DISTINCT p.ci, p.nombres, p.apellidos, p.promedio_final
FROM postulantes p
WHERE p.estado_final = 'REPROBADO'
  AND p.id IN (
      SELECT postulante_id FROM examenes WHERE nota < 50
  );

-- 10. Usuarios cuyo rol es Docente y tienen registro en tabla docentes
SELECT u.username, u.name, u.email
FROM users u
WHERE u.role_id = (SELECT id FROM roles WHERE name = 'Docente')
  AND u.id IN (SELECT user_id FROM docentes);


-- =============================================================================
-- PROCEDIMIENTOS ALMACENADOS (10)
-- PostgreSQL: funciones y procedimientos PL/pgSQL
-- =============================================================================

-- Limpiar objetos previos (permite re-ejecutar el script)
DROP PROCEDURE IF EXISTS sp_registrar_pago(BIGINT, DECIMAL, metodo_pago, VARCHAR);
DROP PROCEDURE IF EXISTS sp_asignar_grupo_postulante(VARCHAR, VARCHAR);
DROP PROCEDURE IF EXISTS sp_cambiar_estado_postulante(VARCHAR, estado_final_postulante);
DROP PROCEDURE IF EXISTS sp_contratar_docente(VARCHAR);
DROP PROCEDURE IF EXISTS sp_desactivar_carrera(VARCHAR);
DROP FUNCTION IF EXISTS fn_promedio_postulante(BIGINT);
DROP FUNCTION IF EXISTS fn_total_aprobados_por_carrera(BIGINT);
DROP FUNCTION IF EXISTS fn_cupo_disponible_grupo(BIGINT);
DROP FUNCTION IF EXISTS fn_buscar_postulante_por_ci(VARCHAR);
DROP FUNCTION IF EXISTS fn_reporte_pagos_por_estado(estado_pago);

-- 1. Registrar pago de un postulante
CREATE OR REPLACE PROCEDURE sp_registrar_pago(
    p_postulante_id      BIGINT,
    p_monto              DECIMAL(10,2),
    p_metodo             metodo_pago,
    p_codigo_transaccion VARCHAR DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO pagos (postulante_id, monto, metodo_pago, codigo_transaccion, estado, created_at, updated_at)
    VALUES (p_postulante_id, p_monto, p_metodo, p_codigo_transaccion, 'PENDIENTE', NOW(), NOW())
    ON CONFLICT (postulante_id) DO UPDATE
        SET monto              = EXCLUDED.monto,
            metodo_pago        = EXCLUDED.metodo_pago,
            codigo_transaccion = EXCLUDED.codigo_transaccion,
            estado             = 'PENDIENTE',
            updated_at         = NOW();
END;
$$;

-- 2. Asignar grupo a postulante por CI y código de grupo
CREATE OR REPLACE PROCEDURE sp_asignar_grupo_postulante(
    p_ci          VARCHAR,
    p_grupo_codigo VARCHAR
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_postulante_id BIGINT;
    v_grupo_id      BIGINT;
BEGIN
    SELECT id INTO v_postulante_id FROM postulantes WHERE ci = p_ci;
    IF v_postulante_id IS NULL THEN
        RAISE EXCEPTION 'Postulante con CI % no encontrado', p_ci;
    END IF;

    SELECT id INTO v_grupo_id FROM grupos WHERE codigo = p_grupo_codigo;
    IF v_grupo_id IS NULL THEN
        RAISE EXCEPTION 'Grupo % no encontrado', p_grupo_codigo;
    END IF;

    UPDATE postulantes
    SET grupo_id = v_grupo_id, updated_at = NOW()
    WHERE id = v_postulante_id;
END;
$$;

-- 3. Cambiar estado final de un postulante
CREATE OR REPLACE PROCEDURE sp_cambiar_estado_postulante(
    p_ci           VARCHAR,
    p_estado_final estado_final_postulante
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE postulantes
    SET estado_final = p_estado_final, updated_at = NOW()
    WHERE ci = p_ci;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Postulante con CI % no encontrado', p_ci;
    END IF;
END;
$$;

-- 4. Contratar docente por CI
CREATE OR REPLACE PROCEDURE sp_contratar_docente(p_ci VARCHAR)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE docentes
    SET contratado = TRUE, estado = TRUE, updated_at = NOW()
    WHERE ci = p_ci;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Docente con CI % no encontrado', p_ci;
    END IF;

    UPDATE users u
    SET status = TRUE, updated_at = NOW()
    FROM docentes d
    WHERE d.user_id = u.id AND d.ci = p_ci;
END;
$$;

-- 5. Desactivar carrera por código
CREATE OR REPLACE PROCEDURE sp_desactivar_carrera(p_codigo VARCHAR)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE carreras
    SET estado = FALSE, updated_at = NOW()
    WHERE codigo = p_codigo;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Carrera % no encontrada', p_codigo;
    END IF;
END;
$$;

-- 6. Obtener promedio calculado de exámenes de un postulante
CREATE OR REPLACE FUNCTION fn_promedio_postulante(p_postulante_id BIGINT)
RETURNS DECIMAL(5,2)
LANGUAGE plpgsql
AS $$
DECLARE
    v_promedio DECIMAL(5,2);
BEGIN
    SELECT ROUND(COALESCE(AVG(nota), 0), 2)
    INTO v_promedio
    FROM examenes
    WHERE postulante_id = p_postulante_id;

    RETURN v_promedio;
END;
$$;

-- 7. Contar postulantes aprobados por carrera
CREATE OR REPLACE FUNCTION fn_total_aprobados_por_carrera(p_carrera_id BIGINT)
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_total INTEGER;
BEGIN
    SELECT COUNT(*)
    INTO v_total
    FROM postulantes
    WHERE carrera_primera_opcion_id = p_carrera_id
      AND estado_final = 'APROBADO';

    RETURN v_total;
END;
$$;

-- 8. Cupos disponibles en un grupo
CREATE OR REPLACE FUNCTION fn_cupo_disponible_grupo(p_grupo_id BIGINT)
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_capacidad INTEGER;
    v_ocupados  INTEGER;
BEGIN
    SELECT capacidad_maxima INTO v_capacidad FROM grupos WHERE id = p_grupo_id;
    SELECT COUNT(*) INTO v_ocupados FROM postulantes WHERE grupo_id = p_grupo_id;
    RETURN v_capacidad - v_ocupados;
END;
$$;

-- 9. Buscar postulante por CI (retorna registro)
CREATE OR REPLACE FUNCTION fn_buscar_postulante_por_ci(p_ci VARCHAR)
RETURNS TABLE (
    ci             VARCHAR,
    nombre_completo TEXT,
    email          VARCHAR,
    promedio_final DECIMAL(5,2),
    estado_final   estado_final_postulante
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT p.ci,
           (p.nombres || ' ' || p.apellidos)::TEXT,
           p.email,
           p.promedio_final,
           p.estado_final
    FROM postulantes p
    WHERE p.ci = p_ci;
END;
$$;

-- 10. Reporte de pagos filtrado por estado
CREATE OR REPLACE FUNCTION fn_reporte_pagos_por_estado(p_estado estado_pago)
RETURNS TABLE (
    pago_id        BIGINT,
    postulante_ci  VARCHAR,
    postulante     TEXT,
    monto          DECIMAL(10,2),
    metodo_pago    metodo_pago,
    fecha_pago     TIMESTAMP
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT pg.id,
           p.ci,
           (p.nombres || ' ' || p.apellidos)::TEXT,
           pg.monto,
           pg.metodo_pago,
           pg.fecha_pago
    FROM pagos pg
    INNER JOIN postulantes p ON pg.postulante_id = p.id
    WHERE pg.estado = p_estado
    ORDER BY pg.fecha_pago;
END;
$$;

-- Ejemplos de llamada a procedimientos/funciones:
-- CALL sp_registrar_pago(5, 150.00, 'Transferencia', 'TRX-2026-0099');
-- CALL sp_asignar_grupo_postulante('7890127', 'GRP-A-2026');
-- CALL sp_cambiar_estado_postulante('7890127', 'APROBADO');
-- CALL sp_contratar_docente('4567893');
-- CALL sp_desactivar_carrera('ING-CIV');
-- SELECT fn_promedio_postulante(1);
-- SELECT fn_total_aprobados_por_carrera(1);
-- SELECT fn_cupo_disponible_grupo(1);
-- SELECT * FROM fn_buscar_postulante_por_ci('7890123');
-- SELECT * FROM fn_reporte_pagos_por_estado('PAGADO');


-- =============================================================================
-- TRIGGERS / DISPARADORES (10)
-- =============================================================================

-- Limpiar triggers y funciones de trigger previos
DROP TRIGGER IF EXISTS trg_users_updated_at ON users;
DROP TRIGGER IF EXISTS trg_postulantes_updated_at ON postulantes;
DROP TRIGGER IF EXISTS trg_pagos_updated_at ON pagos;
DROP TRIGGER IF EXISTS trg_examenes_updated_at ON examenes;
DROP TRIGGER IF EXISTS trg_validar_nota_examen ON examenes;
DROP TRIGGER IF EXISTS trg_validar_monto_pago ON pagos;
DROP TRIGGER IF EXISTS trg_auto_fecha_pago ON pagos;
DROP TRIGGER IF EXISTS trg_recalcular_promedio_insert ON examenes;
DROP TRIGGER IF EXISTS trg_recalcular_promedio_update ON examenes;
DROP TRIGGER IF EXISTS trg_recalcular_promedio_delete ON examenes;
DROP TRIGGER IF EXISTS trg_validar_cupo_grupo ON postulantes;

DROP FUNCTION IF EXISTS fn_set_updated_at();
DROP FUNCTION IF EXISTS fn_validar_nota_examen();
DROP FUNCTION IF EXISTS fn_validar_monto_pago();
DROP FUNCTION IF EXISTS fn_auto_fecha_pago();
DROP FUNCTION IF EXISTS fn_recalcular_promedio_postulante();
DROP FUNCTION IF EXISTS fn_validar_cupo_grupo();

-- Función genérica: actualizar columna updated_at
CREATE OR REPLACE FUNCTION fn_set_updated_at()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$;

-- 1. Trigger: actualizar updated_at en users
CREATE TRIGGER trg_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE fn_set_updated_at();

-- 2. Trigger: actualizar updated_at en postulantes
CREATE TRIGGER trg_postulantes_updated_at
    BEFORE UPDATE ON postulantes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_set_updated_at();

-- 3. Trigger: actualizar updated_at en pagos
CREATE TRIGGER trg_pagos_updated_at
    BEFORE UPDATE ON pagos
    FOR EACH ROW
    EXECUTE PROCEDURE fn_set_updated_at();

-- 4. Trigger: actualizar updated_at en examenes
CREATE TRIGGER trg_examenes_updated_at
    BEFORE UPDATE ON examenes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_set_updated_at();

-- Función: validar rango de nota (0-100)
CREATE OR REPLACE FUNCTION fn_validar_nota_examen()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    IF NEW.nota < 0 OR NEW.nota > 100 THEN
        RAISE EXCEPTION 'La nota debe estar entre 0 y 100. Valor recibido: %', NEW.nota;
    END IF;
    RETURN NEW;
END;
$$;

-- 5. Trigger: validar nota al insertar o actualizar examen
CREATE TRIGGER trg_validar_nota_examen
    BEFORE INSERT OR UPDATE ON examenes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_validar_nota_examen();

-- Función: validar monto positivo en pagos
CREATE OR REPLACE FUNCTION fn_validar_monto_pago()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    IF NEW.monto <= 0 THEN
        RAISE EXCEPTION 'El monto del pago debe ser mayor a cero';
    END IF;
    RETURN NEW;
END;
$$;

-- 6. Trigger: validar monto en pagos
CREATE TRIGGER trg_validar_monto_pago
    BEFORE INSERT OR UPDATE ON pagos
    FOR EACH ROW
    EXECUTE PROCEDURE fn_validar_monto_pago();

-- Función: registrar fecha_pago automáticamente al marcar PAGADO
CREATE OR REPLACE FUNCTION fn_auto_fecha_pago()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    IF NEW.estado = 'PAGADO' AND (OLD.estado IS DISTINCT FROM 'PAGADO') THEN
        NEW.fecha_pago = NOW();
    END IF;
    IF NEW.estado = 'PENDIENTE' THEN
        NEW.fecha_pago = NULL;
    END IF;
    RETURN NEW;
END;
$$;

-- 7. Trigger: auto-asignar fecha_pago
CREATE TRIGGER trg_auto_fecha_pago
    BEFORE UPDATE ON pagos
    FOR EACH ROW
    EXECUTE PROCEDURE fn_auto_fecha_pago();

-- Función: recalcular promedio_final del postulante
CREATE OR REPLACE FUNCTION fn_recalcular_promedio_postulante()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_postulante_id BIGINT;
    v_promedio      DECIMAL(5,2);
BEGIN
    v_postulante_id := COALESCE(NEW.postulante_id, OLD.postulante_id);

    SELECT ROUND(COALESCE(AVG(nota), 0), 2)
    INTO v_promedio
    FROM examenes
    WHERE postulante_id = v_postulante_id;

    UPDATE postulantes
    SET promedio_final = v_promedio,
        updated_at     = NOW()
    WHERE id = v_postulante_id;

    RETURN COALESCE(NEW, OLD);
END;
$$;

-- 8. Trigger: recalcular promedio al insertar examen
CREATE TRIGGER trg_recalcular_promedio_insert
    AFTER INSERT ON examenes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_recalcular_promedio_postulante();

-- 9. Trigger: recalcular promedio al actualizar examen
CREATE TRIGGER trg_recalcular_promedio_update
    AFTER UPDATE ON examenes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_recalcular_promedio_postulante();

-- 10. Trigger: validar cupo disponible al asignar grupo a postulante
CREATE OR REPLACE FUNCTION fn_validar_cupo_grupo()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_capacidad INTEGER;
    v_ocupados  INTEGER;
BEGIN
    IF NEW.grupo_id IS NULL THEN
        RETURN NEW;
    END IF;

    SELECT capacidad_maxima INTO v_capacidad FROM grupos WHERE id = NEW.grupo_id;

    SELECT COUNT(*)
    INTO v_ocupados
    FROM postulantes
    WHERE grupo_id = NEW.grupo_id
      AND id <> NEW.id;

    IF v_ocupados >= v_capacidad THEN
        RAISE EXCEPTION 'El grupo id=% ha alcanzado su capacidad máxima (%)', NEW.grupo_id, v_capacidad;
    END IF;

    RETURN NEW;
END;
$$;

CREATE TRIGGER trg_validar_cupo_grupo
    BEFORE INSERT OR UPDATE OF grupo_id ON postulantes
    FOR EACH ROW
    EXECUTE PROCEDURE fn_validar_cupo_grupo();

-- Nota: el trigger trg_recalcular_promedio_delete queda disponible si se desea
-- recalcular al eliminar exámenes (descomentar si lo necesitas):
-- CREATE TRIGGER trg_recalcular_promedio_delete
--     AFTER DELETE ON examenes
--     FOR EACH ROW
--     EXECUTE PROCEDURE fn_recalcular_promedio_postulante();

