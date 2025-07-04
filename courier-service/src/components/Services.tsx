import React from 'react';
import { Service } from '../types';
import './Services.css';

const Services: React.FC = () => {
  const services: Service[] = [
    {
      id: '1',
      name: 'Express Delivery',
      description: 'Same-day delivery for urgent packages. Perfect for time-sensitive documents and small items.',
      price: 'Starting at $15',
      duration: '2-4 hours',
      icon: '⚡',
    },
    {
      id: '2',
      name: 'Standard Delivery',
      description: 'Reliable next-day delivery for your regular shipping needs. Great value for money.',
      price: 'Starting at $8',
      duration: '1-2 days',
      icon: '📦',
    },
    {
      id: '3',
      name: 'International Shipping',
      description: 'Global delivery solutions with customs handling and tracking. Reach anywhere in the world.',
      price: 'Starting at $25',
      duration: '3-7 days',
      icon: '🌍',
    },
    {
      id: '4',
      name: 'Fragile Handling',
      description: 'Specialized care for delicate items. Extra protection with bubble wrap and careful handling.',
      price: 'Starting at $12',
      duration: '1-3 days',
      icon: '🔒',
    },
    {
      id: '5',
      name: 'Bulk Shipping',
      description: 'Cost-effective solutions for large volume shipments. Perfect for businesses and e-commerce.',
      price: 'Custom pricing',
      duration: '2-5 days',
      icon: '📊',
    },
    {
      id: '6',
      name: 'Cold Chain',
      description: 'Temperature-controlled delivery for pharmaceuticals, food, and other sensitive products.',
      price: 'Starting at $20',
      duration: '1-2 days',
      icon: '❄️',
    },
  ];

  return (
    <section className="services">
      <div className="services-container">
        <div className="services-header">
          <h2 className="services-title">Our Services</h2>
          <p className="services-subtitle">
            Choose from our comprehensive range of courier services designed to meet your specific needs
          </p>
        </div>
        
        <div className="services-grid">
          {services.map((service) => (
            <div key={service.id} className="service-card">
              <div className="service-icon">{service.icon}</div>
              <h3 className="service-name">{service.name}</h3>
              <p className="service-description">{service.description}</p>
              <div className="service-details">
                <div className="service-price">{service.price}</div>
                <div className="service-duration">{service.duration}</div>
              </div>
              <button className="service-btn">Get Quote</button>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Services;