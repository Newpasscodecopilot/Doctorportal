import React, { useState } from 'react';
import { TrackingInfo } from '../types';
import './Tracking.css';

const Tracking: React.FC = () => {
  const [trackingNumber, setTrackingNumber] = useState('');
  const [trackingData, setTrackingData] = useState<TrackingInfo[] | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Mock tracking data for demonstration
  const mockTrackingData: { [key: string]: TrackingInfo[] } = {
    'FC123456789': [
      {
        packageId: 'FC123456789',
        status: 'Package Received',
        location: 'New York Hub',
        timestamp: new Date('2024-01-15T09:00:00'),
        description: 'Package received at origin facility',
      },
      {
        packageId: 'FC123456789',
        status: 'In Transit',
        location: 'Philadelphia Hub',
        timestamp: new Date('2024-01-15T14:30:00'),
        description: 'Package in transit to destination',
      },
      {
        packageId: 'FC123456789',
        status: 'Out for Delivery',
        location: 'Boston Distribution Center',
        timestamp: new Date('2024-01-16T08:00:00'),
        description: 'Package out for delivery',
      },
      {
        packageId: 'FC123456789',
        status: 'Delivered',
        location: 'Boston, MA',
        timestamp: new Date('2024-01-16T15:45:00'),
        description: 'Package delivered successfully',
      },
    ],
    'FC987654321': [
      {
        packageId: 'FC987654321',
        status: 'Package Received',
        location: 'Los Angeles Hub',
        timestamp: new Date('2024-01-14T10:00:00'),
        description: 'Package received at origin facility',
      },
      {
        packageId: 'FC987654321',
        status: 'In Transit',
        location: 'Denver Hub',
        timestamp: new Date('2024-01-15T16:00:00'),
        description: 'Package in transit to destination',
      },
    ],
  };

  const handleTrack = async () => {
    if (!trackingNumber.trim()) {
      setError('Please enter a tracking number');
      return;
    }

    setLoading(true);
    setError('');

    // Simulate API call
    setTimeout(() => {
      const data = mockTrackingData[trackingNumber.toUpperCase()];
      if (data) {
        setTrackingData(data);
      } else {
        setError('Tracking number not found. Please check and try again.');
        setTrackingData(null);
      }
      setLoading(false);
    }, 1000);
  };

  const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
      case 'delivered':
        return '#059669';
      case 'out for delivery':
        return '#ea580c';
      case 'in transit':
        return '#2563eb';
      default:
        return '#6b7280';
    }
  };

  return (
    <section className="tracking">
      <div className="tracking-container">
        <div className="tracking-header">
          <h2 className="tracking-title">Track Your Package</h2>
          <p className="tracking-subtitle">
            Enter your tracking number to get real-time updates on your package status
          </p>
        </div>

        <div className="tracking-form">
          <div className="form-group">
            <input
              type="text"
              placeholder="Enter tracking number (e.g., FC123456789)"
              value={trackingNumber}
              onChange={(e) => setTrackingNumber(e.target.value)}
              className="tracking-input"
              onKeyPress={(e) => e.key === 'Enter' && handleTrack()}
            />
            <button
              onClick={handleTrack}
              disabled={loading}
              className="tracking-btn"
            >
              {loading ? 'Tracking...' : 'Track Package'}
            </button>
          </div>
          {error && <div className="error-message">{error}</div>}
        </div>

        {trackingData && (
          <div className="tracking-results">
            <h3 className="results-title">
              Tracking Results for: <span className="tracking-id">{trackingNumber}</span>
            </h3>
            <div className="timeline">
              {trackingData.map((item, index) => (
                <div
                  key={index}
                  className={`timeline-item ${
                    index === trackingData.length - 1 ? 'current' : ''
                  }`}
                >
                  <div
                    className="timeline-marker"
                    style={{ backgroundColor: getStatusColor(item.status) }}
                  />
                  <div className="timeline-content">
                    <div className="timeline-header">
                      <h4 className="timeline-status">{item.status}</h4>
                      <span className="timeline-time">
                        {item.timestamp.toLocaleString()}
                      </span>
                    </div>
                    <p className="timeline-location">{item.location}</p>
                    <p className="timeline-description">{item.description}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        <div className="demo-info">
          <h4>Demo Tracking Numbers:</h4>
          <p>Try: FC123456789 (delivered) or FC987654321 (in transit)</p>
        </div>
      </div>
    </section>
  );
};

export default Tracking;