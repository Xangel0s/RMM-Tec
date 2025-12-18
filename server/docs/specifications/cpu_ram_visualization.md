# Especificación Técnica: Visualización de Métricas CPU/RAM

## 1. Visión General
Este documento detalla la implementación de la visualización de métricas de rendimiento (CPU y RAM) en el panel de administración de Endpoints. El objetivo es reemplazar la gráfica unificada anterior por dos componentes visuales independientes y altamente estilizados que repliquen un diseño de tablero de monitoreo profesional.

## 2. Requisitos Estructurales

### 2.1. Separación de Componentes
Se han desarrollado dos clases PHP independientes dentro de Filament para manejar la lógica y presentación de cada métrica por separado:

*   **`CpuMetricsChart`**: Responsable exclusivo de los datos de uso de CPU.
*   **`MemoryMetricsChart`**: Responsable exclusivo de los datos de uso de Memoria RAM.

Esta separación garantiza:
*   **Independencia de datos**: Fallos en la recolección de una métrica no afectan a la otra.
*   **Escalabilidad**: Permite añadir más métricas o personalizar cada gráfica sin afectar al resto.
*   **Mantenimiento**: Facilita la modificación de estilos o lógica específica por componente.

### 2.2. Integración en Filament
Ambos componentes se registran como `FooterWidgets` en la página `EditEndpoint`, apareciendo automáticamente al pie del formulario de edición del endpoint.

## 3. Especificaciones de Diseño Visual

El diseño implementado busca la paridad visual "pixel-perfect" con las referencias de monitoreo de sistemas modernos (estilo Grafana/Datadog), adaptado al ecosistema Filament.

### 3.1. Estilos Comunes
*   **Tema Oscuro**: Optimizado para el modo oscuro de Filament (`bg-gray-900` implícito).
*   **Tipografía**: Sans-serif, tamaños optimizados para alta densidad de información.
*   **Ejes y Grillas**:
    *   Eje Y: Escala fija 0-100%. Líneas de cuadrícula sutiles (`#374151`).
    *   Eje X: Etiquetas de tiempo (`H:i:s`), sin líneas de cuadrícula verticales para mayor limpieza.
    *   Etiquetas: Color gris medio (`#9ca3af`) para reducir ruido visual.
*   **Interacción**: Tooltips desactivados o minimalistas, modo `index` para facilitar la lectura puntual.
*   **Puntos**: Radio 0 (invisibles) por defecto, aparecen (radio 4) al pasar el mouse (hover).

### 3.2. Gráfica de CPU (`CpuMetricsChart`)
*   **Color Principal**: Verde Neón (`#4ade80`).
*   **Relleno**: Mínimo/Nulo (`rgba(74, 222, 128, 0.1)`). Se prioriza la visualización de la línea ("Line Chart") para mostrar picos y volatilidad.
*   **Tensión**: 0.4 (Curvas suaves).

### 3.3. Gráfica de Memoria (`MemoryMetricsChart`)
*   **Color Principal**: Azul Corporativo (`#3b82f6`).
*   **Relleno**: Significativo (`rgba(59, 130, 246, 0.5)`). Se utiliza un gráfico de área ("Area Chart") para denotar volumen de uso acumulado.
*   **Tensión**: 0.4.

## 4. Validación Técnica

### 4.1. Comparación con Referencia
| Característica | Referencia (Imagen) | Implementación Actual |
| :--- | :--- | :--- |
| **Separación** | Dos paneles distintos | Dos Widgets independientes |
| **CPU Color** | Línea Verde | `#4ade80` (Verde) |
| **Mem Color** | Área Azul | `#3b82f6` (Azul) con relleno |
| **Ejes** | Grid sutil, etiquetas blancas/grises | Grid `#374151`, etiquetas `#9ca3af` |
| **Layout** | Side-by-side (Dashboard) | Stacked/Grid (Filament default) |

*Nota: La disposición (lado a lado o apilada) depende del ancho de pantalla y la configuración de columnas del Dashboard de Filament.*

### 4.2. Responsividad
Los componentes utilizan el wrapper `Chart.js` de Filament, que es intrínsecamente responsivo (`resize: true`). Se adaptan automáticamente a:
*   **Desktop**: Visualización completa con historial de 30 puntos.
*   **Tablet/Mobile**: Reducción de escala manteniendo la legibilidad de la línea de tendencia.

## 5. Guía de Mantenimiento

### 5.1. Ajuste de Colores
Para modificar los colores, editar las propiedades `borderColor` y `backgroundColor` en el método `getData()` de cada clase:

```php
// CpuMetricsChart.php
'borderColor' => '#nuevo_color',
```

### 5.2. Configuración de Ejes
Las escalas se definen en `getOptions()['scales']`. Para cambiar el rango (ej. si la memoria se mide en GB absolutos en el futuro):

```php
'y' => [
    'min' => 0,
    'max' => 100, // Cambiar a capacidad máxima en GB
    // ...
],
```

### 5.3. Intervalo de Actualización
Por defecto configurado en `5s`. Modificar la propiedad estática:

```php
protected static ?string $pollingInterval = '10s';
```
