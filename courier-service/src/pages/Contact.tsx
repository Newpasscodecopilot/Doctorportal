import React, { useState } from 'react';
import { ContactForm } from '../types';
import './Contact.css';

const Contact: React.FC = () => {
  const [formData, setFormData] = useState<ContactForm>({
    name: '',
    email: '',
    phone: '',
    message: '',
  });
  const [submitted, setSubmitted] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Here you would typically send the form data to your backend
    console.log('Form submitted:', formData);
    setSubmitted(true);
    
    // Reset form after 3 seconds
    setTimeout(() => {
      setSubmitted(false);
      setFormData({
        name: '',
        email: '',
        phone: '',
        message: '',
      });
    }, 3000);
  };

  return (
    <div className="contact-page">
      <div className="contact-container">
        <div className="contact-hero">
          <h1 className="contact-title">Get In Touch</h1>
          <p className="contact-subtitle">
            We're here to help! Contact us for any questions about our services or to get a quote.
          </p>
        </div>

        <div className="contact-content">
          <div className="contact-info">
            <h2>Contact Information</h2>
            <div className="contact-methods">
              <div className="contact-method">
                <div className="contact-icon">📞</div>
                <div className="contact-details">
                  <h3>Phone</h3>
                  <p>+1 (555) 123-4567</p>
                  <p>Available 24/7</p>
                </div>
              </div>
              
              <div className="contact-method">
                <div className="contact-icon">📧</div>
                <div className="contact-details">
                  <h3>Email</h3>
                  <p>info@fastcourier.com</p>
                  <p>We'll respond within 24 hours</p>
                </div>
              </div>
              
              <div className="contact-method">
                <div className="contact-icon">📍</div>
                <div className="contact-details">
                  <h3>Address</h3>
                  <p>123 Courier Street</p>
                  <p>New York, NY 10001</p>
                </div>
              </div>
              
              <div className="contact-method">
                <div className="contact-icon">🕒</div>
                <div className="contact-details">
                  <h3>Business Hours</h3>
                  <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
                  <p>Saturday: 9:00 AM - 4:00 PM</p>
                </div>
              </div>
            </div>
          </div>

          <div className="contact-form-section">
            <h2>Send us a Message</h2>
            {submitted ? (
              <div className="success-message">
                <div className="success-icon">✅</div>
                <h3>Thank you for your message!</h3>
                <p>We'll get back to you as soon as possible.</p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="contact-form">
                <div className="form-row">
                  <div className="form-group">
                    <label htmlFor="name">Full Name *</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      value={formData.name}
                      onChange={handleChange}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="email">Email Address *</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      value={formData.email}
                      onChange={handleChange}
                      required
                    />
                  </div>
                </div>
                
                <div className="form-group">
                  <label htmlFor="phone">Phone Number</label>
                  <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value={formData.phone}
                    onChange={handleChange}
                  />
                </div>
                
                <div className="form-group">
                  <label htmlFor="message">Message *</label>
                  <textarea
                    id="message"
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    rows={6}
                    required
                    placeholder="Tell us about your delivery needs..."
                  />
                </div>
                
                <button type="submit" className="submit-btn">
                  Send Message
                </button>
              </form>
            )}
          </div>
        </div>

        <div className="map-section">
          <h2>Find Us</h2>
          <div className="map-placeholder">
            <div className="map-content">
              <div className="map-pin">📍</div>
              <p>Interactive Map</p>
              <p>123 Courier Street, New York, NY 10001</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Contact;