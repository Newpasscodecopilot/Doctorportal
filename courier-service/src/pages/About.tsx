import React from 'react';
import './About.css';

const About: React.FC = () => {
  return (
    <div className="about-page">
      <div className="about-container">
        <div className="about-hero">
          <h1 className="about-title">About FastCourier</h1>
          <p className="about-subtitle">
            Your trusted partner in fast, reliable, and secure delivery services since 2010
          </p>
        </div>

        <div className="about-content">
          <div className="about-section">
            <div className="about-text">
              <h2>Our Story</h2>
              <p>
                FastCourier was founded in 2010 with a simple mission: to provide the fastest, 
                most reliable courier services in the industry. What started as a small local 
                delivery service has grown into a nationwide network of trusted professionals 
                committed to getting your packages where they need to be, when they need to be there.
              </p>
              <p>
                Over the years, we've handled millions of deliveries, from urgent documents to 
                precious cargo, always maintaining our commitment to excellence and customer satisfaction.
              </p>
            </div>
            <div className="about-image">
              <div className="about-illustration">
                <div className="delivery-fleet">
                  <div className="vehicle">🚛</div>
                  <div className="vehicle">🚐</div>
                  <div className="vehicle">🚚</div>
                </div>
              </div>
            </div>
          </div>

          <div className="mission-section">
            <h2>Our Mission</h2>
            <p>
              To revolutionize the courier industry by providing exceptional service, 
              cutting-edge technology, and unwavering reliability. We believe that every 
              package tells a story, and we're honored to be part of that journey.
            </p>
          </div>

          <div className="values-section">
            <h2>Our Values</h2>
            <div className="values-grid">
              <div className="value-card">
                <div className="value-icon">⚡</div>
                <h3>Speed</h3>
                <p>We understand that time is precious. Our commitment to speed means your packages arrive when you need them to.</p>
              </div>
              <div className="value-card">
                <div className="value-icon">🔒</div>
                <h3>Security</h3>
                <p>Your packages are safe with us. We use advanced tracking and security measures to protect your valuable items.</p>
              </div>
              <div className="value-card">
                <div className="value-icon">💯</div>
                <h3>Reliability</h3>
                <p>Count on us to deliver. Our 99.9% success rate speaks to our commitment to getting things right.</p>
              </div>
              <div className="value-card">
                <div className="value-icon">🤝</div>
                <h3>Service</h3>
                <p>Our customers come first. We're always here to help, support, and ensure your satisfaction.</p>
              </div>
            </div>
          </div>

          <div className="stats-section">
            <h2>By the Numbers</h2>
            <div className="stats-grid">
              <div className="stat-item">
                <div className="stat-number">2M+</div>
                <div className="stat-label">Packages Delivered</div>
              </div>
              <div className="stat-item">
                <div className="stat-number">50+</div>
                <div className="stat-label">Cities Served</div>
              </div>
              <div className="stat-item">
                <div className="stat-number">99.9%</div>
                <div className="stat-label">Success Rate</div>
              </div>
              <div className="stat-item">
                <div className="stat-number">24/7</div>
                <div className="stat-label">Customer Support</div>
              </div>
            </div>
          </div>

          <div className="team-section">
            <h2>Our Team</h2>
            <p>
              Behind every successful delivery is a dedicated team of professionals. From our 
              skilled drivers to our customer service representatives, everyone at FastCourier 
              is committed to providing you with the best possible experience.
            </p>
            <div className="team-grid">
              <div className="team-member">
                <div className="team-avatar">👨‍💼</div>
                <h4>John Smith</h4>
                <p>CEO & Founder</p>
              </div>
              <div className="team-member">
                <div className="team-avatar">👩‍💼</div>
                <h4>Sarah Johnson</h4>
                <p>Operations Manager</p>
              </div>
              <div className="team-member">
                <div className="team-avatar">👨‍🔧</div>
                <h4>Mike Wilson</h4>
                <p>Fleet Manager</p>
              </div>
              <div className="team-member">
                <div className="team-avatar">👩‍💻</div>
                <h4>Emily Chen</h4>
                <p>Customer Success</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default About;