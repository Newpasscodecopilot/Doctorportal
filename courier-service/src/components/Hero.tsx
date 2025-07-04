import React from 'react';
import { Link } from 'react-router-dom';
import './Hero.css';

const Hero: React.FC = () => {
  return (
    <section className="hero">
      <div className="hero-container">
        <div className="hero-content">
          <h1 className="hero-title">
            Fast & Reliable
            <span className="hero-highlight"> Courier Services</span>
          </h1>
          <p className="hero-description">
            Experience lightning-fast delivery with our professional courier services. 
            We ensure your packages reach their destination safely and on time, every time.
          </p>
          <div className="hero-buttons">
            <Link to="/services" className="btn btn-primary">
              Our Services
            </Link>
            <Link to="/tracking" className="btn btn-secondary">
              Track Package
            </Link>
          </div>
          <div className="hero-stats">
            <div className="stat">
              <span className="stat-number">10K+</span>
              <span className="stat-label">Happy Customers</span>
            </div>
            <div className="stat">
              <span className="stat-number">50K+</span>
              <span className="stat-label">Deliveries Made</span>
            </div>
            <div className="stat">
              <span className="stat-number">99.9%</span>
              <span className="stat-label">Success Rate</span>
            </div>
          </div>
        </div>
        <div className="hero-image">
          <div className="hero-illustration">
            <div className="delivery-truck">🚚</div>
            <div className="package-icons">
              <div className="package">📦</div>
              <div className="package">📦</div>
              <div className="package">📦</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;