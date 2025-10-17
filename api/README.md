# Doctor Patient Portal API Documentation

## Overview

The Doctor Patient Portal API provides RESTful endpoints for mobile applications and third-party integrations. All responses are in JSON format.

## Base URL

```
http://your-domain.com/api/
```

## Authentication

### POST /api/auth

Authenticate user and receive access token.

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "type": "doctor"
    }
}
```

## Doctors

### GET /api/doctors

Get list of all verified doctors.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "did": 1,
            "name": "Dr. John Smith",
            "email": "doctor@example.com",
            "age": 45,
            "gender": "Male",
            "phone_number": "9876543210",
            "doccat": "Cardiologist",
            "status": "success"
        }
    ],
    "count": 1
}
```

### GET /api/doctors/{id}

Get specific doctor details.

**Response:**
```json
{
    "success": true,
    "data": {
        "did": 1,
        "name": "Dr. John Smith",
        "email": "doctor@example.com",
        "age": 45,
        "gender": "Male",
        "phone_number": "9876543210",
        "doccat": "Cardiologist",
        "status": "success"
    }
}
```

## Appointments

### GET /api/appointments

Get list of all appointments.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "docid": "DOC001",
            "pid": "PAT001",
            "date": "2024-01-15",
            "time": "10:00",
            "problem": "Regular checkup",
            "status": "pending",
            "doctor_name": "Dr. John Smith",
            "patient_name": "Jane Doe"
        }
    ],
    "count": 1
}
```

### POST /api/appointments

Create new appointment.

**Request Body:**
```json
{
    "docid": "DOC001",
    "pid": "PAT001",
    "date": "2024-01-15",
    "time": "10:00",
    "problem": "Regular checkup"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment created successfully",
    "id": 123
}
```

## Statistics

### GET /api/stats

Get system statistics.

**Response:**
```json
{
    "success": true,
    "data": {
        "doctors": 25,
        "patients": 150,
        "appointments": 300,
        "pending_appointments": 15
    }
}
```

## Error Responses

All error responses follow this format:

```json
{
    "error": "Error message description"
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `405` - Method Not Allowed
- `500` - Internal Server Error

## Rate Limiting

API requests are limited to 100 requests per minute per IP address.

## CORS

The API supports Cross-Origin Resource Sharing (CORS) for web applications.

## Examples

### JavaScript/Fetch

```javascript
// Authentication
const response = await fetch('/api/auth', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        email: 'doctor@example.com',
        password: 'password123'
    })
});

const data = await response.json();
console.log(data);

// Get doctors
const doctors = await fetch('/api/doctors');
const doctorData = await doctors.json();
console.log(doctorData);
```

### cURL

```bash
# Authentication
curl -X POST http://your-domain.com/api/auth \
  -H "Content-Type: application/json" \
  -d '{"email":"doctor@example.com","password":"password123"}'

# Get doctors
curl http://your-domain.com/api/doctors

# Create appointment
curl -X POST http://your-domain.com/api/appointments \
  -H "Content-Type: application/json" \
  -d '{
    "docid": "DOC001",
    "pid": "PAT001", 
    "date": "2024-01-15",
    "time": "10:00",
    "problem": "Regular checkup"
  }'
```

## Security

- All inputs are sanitized and validated
- SQL injection protection through prepared statements
- Rate limiting to prevent abuse
- HTTPS recommended for production use

## Support

For API support and questions, please contact the development team.