// Dashboard.jsx

import React, { useState, useEffect } from "react";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import $ from 'jquery';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const Dashboard = () => {
    const [formDataFlight, setFormDataFlight] = useState({
        direct: false,
        currency: "COP",
        searchs: 50,
        class: false,
        qtyPassengers: 1,
        adult: 1,
        child: 0,
        baby: 0,
        seat: 0,
        itinerary: [
            {
                departureCity: "MDE",
                arrivalCity: "YYZ",
                hour: "2024-10-31T05:00:00.000Z",
            },
        ],
    });

    const [formDataAirport, setformDataAirport] = useState({
        code: 'medell'
    });

    // Estado para almacenar los segmentos después de la respuesta
    const [segments, setSegments] = useState([]);
    const [airports, setAirports] = useState([]);
    const [cities, setCities] = useState([]);

    // Maneja el cambio de valores en los inputs
    const handleInputChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormDataFlight({
            ...formDataFlight,
            [name]: type === "checkbox" ? checked : value,
        });
        setformDataAirport({
            ...formDataAirport,
            [name]: type === "checkbox" ? checked : value,
        });
    };

    // Maneja el cambio en los itinerarios
    const handleItineraryChange = (index, e) => {
        const { name, value } = e.target;
        const newItinerary = [...formDataFlight.itinerary];
        newItinerary[index][name] = value;
        setFormDataFlight({ ...formDataFlight, itinerary: newItinerary });
    };

    // Agrega una nueva fila de itinerario
    const addItinerary = () => {
        setFormDataFlight({
          ...formDataFlight,
          itinerary: [
            ...formDataFlight.itinerary,
            {
              departureCity: '',
              arrivalCity: '',
              hour: '', // Iniciar con una cadena vacía para evitar problemas con la fecha
            },
          ],
        });
      };

      // Elimina una fila de itinerario
      const removeItinerary = (index) => {
        const newItinerary = formDataFlight.itinerary.filter((_, i) => i !== index);
        setFormDataFlight({ ...formDataFlight, itinerary: newItinerary });
      };

    // Envía la solicitud POST y maneja la respuesta
    const flightSubmit = async (e) => {
        e.preventDefault();
        const authToken = localStorage.getItem("authToken");
        try {
            const response = await axios.post(
                "http://wellezy-prueba-tecnica.test/api/flights",
                formDataFlight,
                {
                    headers: {
                        Authorization: `Bearer ${authToken}`,
                    },
                }
            );

            if (response.data.data.data.status === 200) {
                // Guarda los segmentos en el estado si la respuesta es exitosa
                const segmentsData = response.data.data.data.data.Seg1.flatMap(
                    (item) => item.segments
                );
                setSegments(segmentsData);
            }
        } catch (error) {
            if (error.response.status == 422) {
                const errorMessages = error.response.data.errors;
                Object.values(errorMessages).forEach((errorArray) => {
                  errorArray.forEach((msg) => {
                    alert(msg)
                  });
                });
              } else {
                alert('Error al hacer la solicitud: ' + error.message)
              }
        }
    };


    const airportSubmit = async (e) => {
        e.preventDefault();
        const authToken = localStorage.getItem("authToken");
        try {
            const response = await axios.post(
                "http://wellezy-prueba-tecnica.test/api/airports",
                formDataAirport,
                {
                    headers: {
                        Authorization: `Bearer ${authToken}`,
                    },
                }
            );

            if (response.data.StatusCode === 200) {

                setCities(response.data.data.data.cities);
                setAirports(response.data.data.data.airports);
            }
        } catch (error) {
            if (error.response.status == 422) {
                const errorMessages = error.response.data.errors;
                Object.values(errorMessages).forEach((errorArray) => {
                  errorArray.forEach((msg) => {
                    alert(msg)
                  });
                });
              } else {
                alert('Error al hacer la solicitud: ' + error.message)
              }
        }
    };

    useEffect(() => {
        if (segments.length > 0) {
          const table = $('#flightsTable').DataTable();
          return () => table.destroy(); // Destruir la instancia de DataTable al desmontar
        }
      }, [segments]);

    // Inicializar DataTable para la tabla de aeropuertos
    useEffect(() => {
      if (airports.length > 0) {
        const table = $('#airportsTable').DataTable();
        return () => table.destroy(); // Destruir la instancia de DataTable al desmontar
      }
    }, [airports]);

    // Inicializar DataTable para la tabla de aeropuertos
    useEffect(() => {
      if (cities.length > 0) {
        const table = $('#citiesTable').DataTable();
        return () => table.destroy(); // Destruir la instancia de DataTable al desmontar
      }
    }, [cities]);

    return (
        <div style={{ overflowY: 'scroll', maxHeight: '500px' }}>
            <div>
            <h1>AEROPUERTOS</h1>
                {/* Formulario de búsqueda */}
                <form onSubmit={airportSubmit}>
                    {/* Inputs generales */}

                    <div>
                        <label> Codigo:
                            <input type="text" name="code" value={formDataAirport.code} onChange={handleInputChange} />
                        </label>
                    </div>

                    <button className="btn btn-primary m-2" type="submit">Enviar</button>
                </form>


                <div className="overflow-auto mt-4">
                    {cities.length > 0 && (
                    <table id="citiesTable" className="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th>Ciudad</th>
                            <th>Aeropuerto</th>
                            <th>Código IATA</th>
                            <th>País</th>
                            <th>Código de País (ISO2)</th>
                            <th>Población</th>
                            <th>Capital</th>
                            </tr>
                        </thead>
                        <tbody>
                            {cities.map((city) =>
                            city.new_airports.map((airport) => (
                                <tr key={airport.airportId}>
                                <td>{city.nameCity}</td>
                                <td>{airport.nameAirport}</td>
                                <td>{airport.codeIataAirport}</td>
                                <td>{city.new_country.nameCountry}</td>
                                <td>{city.new_country.codeIso2Country}</td>
                                <td>{city.new_country.population}</td>
                                <td>{city.new_country.capital}</td>
                                </tr>
                            ))
                            )}
                        </tbody>
                    </table>
                    )}
                </div>

                <div className="overflow-auto mt-4">
                    {airports.length > 0 && (
                    <table id="airportsTable" className="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Airport ID</th>
                            <th>Code IATA</th>
                            <th>Name Airport</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Timezone</th>
                        </tr>
                        </thead>
                        <tbody>
                        {airports.map((airport) => (
                            <tr key={airport.airportId}>
                            <td>{airport.airportId}</td>
                            <td>{airport.codeIataAirport}</td>
                            <td>{airport.nameAirport}</td>
                            <td>{airport.new_city.nameCity}</td>
                            <td>{airport.new_country.nameCountry}</td>
                            <td>{airport.timezone}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                    )}
                </div>
            </div>
            <div>
                <h1>VUELOS</h1>
                {/* Formulario de búsqueda */}
                <form onSubmit={flightSubmit}>
                    {/* Inputs generales */}
                    <div>
                        <label> Directo:
                            <input type="checkbox" name="direct" checked={formDataFlight.direct} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Moneda:
                            <input type="text" name="currency" value={formDataFlight.currency} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Búsquedas:
                            <input type="number" name="searchs" value={formDataFlight.searchs} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Clase económica:
                            <input type="checkbox" name="class" checked={formDataFlight.class} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label>Cantidad de pasajeros:
                            <input type="number" name="qtyPassengers" value={formDataFlight.qtyPassengers} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Adultos:
                            <input type="number" name="adult" value={formDataFlight.adult} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Niños:
                            <input type="number" name="child" value={formDataFlight.child} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Bebés:
                            <input type="number" name="baby" value={formDataFlight.baby} onChange={handleInputChange} />
                        </label>
                    </div>

                    <div>
                        <label> Asiento:
                            <input type="number" name="seat" value={formDataFlight.seat} onChange={handleInputChange} />
                        </label>
                    </div>

                    {/* Itinerarios */}
                    {formDataFlight.itinerary.map((itinerary, index) => (
                        <div key={index}>
                            <h3>Itinerario {index + 1}</h3>
                            <label> Ciudad de salida:
                                <input type="text" name="departureCity" value={itinerary.departureCity} onChange={(e) => handleItineraryChange(index, e) } />
                            </label>
                            <label> Ciudad de llegada:
                                <input type="text" name="arrivalCity" value={itinerary.arrivalCity} onChange={(e) => handleItineraryChange(index, e) } />
                            </label>
                            <label> Hora:
                                <input type="datetime-local" name="hour" value={ itinerary.hour && !isNaN(new Date(itinerary.hour).getTime()) ? new Date(itinerary.hour).toISOString().slice(0, 16) : '' } onChange={(e) => handleItineraryChange(index, e) } />
                            </label>
                            <button className="btn btn-danger m-2" type="button" onClick={() => removeItinerary(index)} >
                                Eliminar
                            </button>
                        </div>
                    ))}

                    <button className="btn btn-success" type="button" onClick={addItinerary}>
                        Agregar
                    </button>

                    <button className="btn btn-primary m-2" type="submit">Enviar</button>
                </form>

                {/* Tabla para mostrar los datos de segments */}

                <div className="overflow-auto mt-4">
                {segments.length > 0 && (
                    <table id="flightsTable" className="table table-striped table-bordered mt-4">
                    <thead>
                        <tr>
                        <th>Fecha de Salida</th>
                        <th>Hora de Salida</th>
                        <th>Fecha de Llegada</th>
                        <th>Hora de Llegada</th>
                        <th>Número de Vuelo</th>
                        <th>Compañía</th>
                        <th>Tipo de Atributo</th>
                        <th>Descripción del Atributo</th>
                        <th>Equipo</th>
                        </tr>
                    </thead>
                        <tbody>
                            {segments.map((segment, index) => (
                            <tr key={index}>
                                <td>{segment.productDateTime.dateOfDeparture}</td>
                                <td>{segment.productDateTime.timeOfDeparture}</td>
                                <td>{segment.productDateTime.dateOfArrival}</td>
                                <td>{segment.productDateTime.timeOfArrival}</td>
                                <td>{segment.flightOrtrainNumber}</td>
                                <td>{segment.companyId.marketingCarrier}</td>
                                <td>{segment.attributeDetail.attributeType}</td>
                                <td>{segment.attributeDetail.attributeDescription}</td>
                                <td>{segment.equipment}</td>
                            </tr>
                            ))}
                        </tbody>
                    </table>
                )}
            </div>
            </div>
        </div>
    );
};

export default Dashboard;
