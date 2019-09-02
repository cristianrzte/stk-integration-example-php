# stk-integration-example-php

Integraciones de recepción de reportes generados por terceros



# Datos Obligatorios
* Patente del registro nacional del automotor
* Marca (fabricante)
* Modelo
* Año de fabricación

# Datos Adicionales
* N° Chasis
* N° Motor
* Color

# Implementación

El sistema externo debe enviar paquetes del protocolo HTTP mediante el método PUT hacia el cluster de servidores receptores de ~~~~~ en tiempo real (o con el menor retardo posible, idealmente menos de 10 segundos de retardo), incluyendo en el contenido (body) del paquete un solo registro/objeto en formato [JSON](http://json.org/) indicando la cabecera HTTP correspondiente: Content-Type: application/json

Cada invocación HTTP debe ser autenticada con una nueva firma digital generada dinámicamente por el sistema generador de reportes a partir de una clave secreta de aplicación que ~~~~~ entrega a los encargados del sistema externo.

Tal clave de aplicación es válida para generar firmas de autenticación para cualquier unidad/equipo previamente dado de alta. El método de generación de firma digital se especifica en el siguiente apartado



# Descripción de los datos a enviar
# Datos Obligatorios
Todos los reportes deben contener los siguientes datos:

loginCode :string: identificador del vehículo generado por ~~~~~ Una vez que ~~~~~ da de alta los equipos virtuales asociados a los vehículos indicados, enviará el identificador de vehículo/equipo inmutable llamado loginCode por cada unidad. Este valor no deberá cambiar a pesar de que sea reemplazado el equipo/dispositivo GPS físico en el vehículo.

reportDate :string: fecha y hora de generación del reporte. El formato estándar de fecha para el intercambio de datos es ISO 8601 adoptado por el W3C: “yyyy-MM-ddTHH:mm:ss-ZZ:XX” [W3C DATETIME](http://www.w3.org/TR/NOTE-datetime). Se recomienda utilizar huso horario UTC ​+​00​:00, el cual se puede representar abreviado con el caracter Z, de la siguiente manera: ​“yyyy-MM-ddTHH:mm:ssZ”

reportType :string: identificador de tipo de reporte/evento que se está enviando. Se utilizará preferentemente el identificador numérico definido por ~~~~~ para cada tipo de evento, el listado completo se encuentra en la siguiente sección: Tipo de Reporte/Evento También es posible que los identificadores sean definidos arbitrariamente por otro sistema integrado indirectamente o por el mismo equipo GPS generador de reportes, en cuyo caso se debe compartir la correspondencia de códigos mediante un archivo adjunto por correo electrónico.

latitude :double: Latitud de la posición expresada en grados decimales.

longitude :double: Longitud de la posición expresada en grados decimales.

gpsDop:double: Dispersion de la precisión GPS. Los valores que representan coordenadas confiables son menores a 2. El valor 2 o más indica coordenadas poco precisas, por lo que los reportes pueden ser catalogados como inválidos y para poder observarlos es necesario activar filtros especiales.

(0.0 - 0.9) excelente
(1.0 - 1.9) buena
(2.0 - 4.9) moderada
(5.0 - 9.9) escasa
(10.0 - 19.9) pobre
(20.0 - ∞) pésima

Referencia: https://en.wikipedia.org/wiki/Dilution_of_precision_(navigation)

Importante: Si no se cuenta con este campo, es necesario el envío del campo gpsValidity, descripto más abajo en este documento.


# Datos Adicionales
Los siguientes campos adicionales deben enviarse siempre que sea posible, y cuando no estén disponibles debe omitirse la inclusión de su nombre en el registro JSON:

heading:integer: Rumbo de circulación expresado en grados sexagecimales relativos al Norte magnético ubicado en 0°.

speed:double: Velocidad de movimientos expresada en Km/h.

speedLabel:double: Tipo de velocidad: GPS, ECU, ECU_MAX, ECU_AVG, etc

gpsValidity:integer: validez de los datos de posición. No es necesario si se proporciona “gpsDop”. El proveedor externo de reportes debe indicar durante la etapa de desarrollo cuáles son los posibles valores que se enviarán al sistema ~~~~~ y cuales deben ser considerados como datos válidos. De manera predeterminada son considerados válidos los valores mayores a 0 y menores que 90.

gpsSatellites:integer: Cantidad de satélites utilizados en cada cálculo de coordenadas.

text:string: texto asociado al reporte utilizado solamente como información adicional ajena a validaciones.

textLabel:string: Tipo de texto: TAG (uso general), VEHICLE_ID (VIN/chasis o id registro nacional automotor)

​altitude:double: Altura sobre el nivel del mar expresada en metros.

altitudeLabel:string: Método de cálculo de la altura, por GPS, BAROMETER, etc.

odometer:double: Cuentakilómetros, odómetro expresado en kilómetros.

odometerLabel:string: Tipo de odómetro, calculado por GPS, ECU, etc.

hourMeter:double: Contador acumulado de tiempo expresado en horas.

hourMeterLabel:string: Tipo de contador acumulado de tiempo expresado en horas: ENGINE_ON, ECU_ENGINE_ON, DRIVING, etc

temperature:double: Temperatura

temperatureLabel:string: Tipo de temperatura: STORAGE_1, STORAGE_2, ECU_COOLANT, etc

volume:double: Volumen

volumeLabel:string: Tipo de volumen: TANK_1, TANK_2, CARGO_COMPARTMENT, ECU_TANK, etc

rpm:double: Revoluciones por minuto del motor

rpmLabel:string: Tipo de revoluciones por minuto del motor

percentage:double: Porcentaje

percentageLabel:string: Tipo de porcentaje: TANK_1, TANK_2, CARGO_COMPARTMENT, ECU_TANK, etc

pressure:double: Presión

pressureLabel:string: Tipo de presión

consumption:double: Consumo

consumptionLabel:string: Tipo de consumo

power:double: Potencia

powerLabel:string: Tipo de potencia

time:double: Tiempo expresado en segundos

timeLabel:string: Tipo de tiempo medido

weight:double: Peso

weightLabel:string: Tipo de peso

distance:double: Distancia

distanceLabel:string: Tipo de distancia

voltage:double: Voltaje

voltageLabel:string: Tipo de voltaje
