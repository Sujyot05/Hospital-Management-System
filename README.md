# Hospital Management System
This is a simple Hospital Management System built using **PHP and MySQL**
The project helps manage patients, doctors, appointments, and billing in an easy way.

##  What this project does
* Patients can register and log in
* Patients can book appointments with doctors
* Admin can accept or reject appointments
* Admin can generate bills after appointment is accepted
* Payment status can be marked as paid
* 
##  Patient Side
* Register & Login
* Book Appointment
* View Appointment Status
* Cancel Appointment

##  Admin Side

* Login (fixed credentials in code)
* Add and manage doctors
* View all appointments
* Accept / Reject appointments
* Generate billing
* Mark payment as paid
* 
##  Tech Used
* HTML, CSS
* PHP
* MySQL
  
##  How to run

1. Install XAMPP
2. Start Apache and MySQL
3. Put project folder inside `htdocs`
4. Import database in MySQL
5. Open in browser:

   http://localhost/your-folder


##  Admin Login

```
Email: admin@pune.org
Password: Pass@123


##  Note

* Billing is only generated after appointment is accepted
* Each appointment has only one bill
* Basic validation and session handling is implemented

---
##  **Database Schemas**

PATIENTS TABLE
CREATE TABLE Patients (
patient_id INT AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(50) NOT NULL,
last_name VARCHAR(50),
gender ENUM('Male','Female','Other'),
dob DATE,
phone VARCHAR(15) UNIQUE,
email VARCHAR(100) UNIQUE,
govt_id VARCHAR(50),
address TEXT,
blood_group VARCHAR(5),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DOCTORS TABLE
CREATE TABLE Doctors (
doctor_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
specialization VARCHAR(100),
phone VARCHAR(15) UNIQUE,
email VARCHAR(100) UNIQUE,
experience_years INT,
consultation_fee DECIMAL(10,2)
);

--  APPOINTMENTS TABLE
CREATE TABLE Appointments (
appointment_id INT AUTO_INCREMENT PRIMARY KEY,
patient_id INT,
doctor_id INT,
appointment_date DATE,
appointment_time TIME,
status ENUM('Pending','Accepted','Cancelled','Cancelled by User') DEFAULT 'Pending',
reason TEXT,

FOREIGN KEY (patient_id) REFERENCES Patients(patient_id)
    ON DELETE CASCADE,
FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id)
    ON DELETE CASCADE

);

--  BILLING TABLE
CREATE TABLE Billing (
bill_id INT AUTO_INCREMENT PRIMARY KEY,
appointment_id INT UNIQUE,
total_amount DECIMAL(10,2),
payment_status ENUM('Pending','Paid') DEFAULT 'Pending',
payment_method VARCHAR(50),
bill_date DATE,

FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id)
    ON DELETE CASCADE

);

##  Author
github:Sujyot05
Linkedin:https://www.linkedin.com/in/sujyot-bhandare/
