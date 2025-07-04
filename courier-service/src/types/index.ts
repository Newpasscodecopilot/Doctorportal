export interface Package {
  id: string;
  sender: string;
  receiver: string;
  origin: string;
  destination: string;
  status: 'pending' | 'in-transit' | 'delivered' | 'cancelled';
  createdAt: Date;
  estimatedDelivery: Date;
  weight: number;
  dimensions: {
    length: number;
    width: number;
    height: number;
  };
}

export interface Service {
  id: string;
  name: string;
  description: string;
  price: string;
  duration: string;
  icon: string;
}

export interface TrackingInfo {
  packageId: string;
  status: string;
  location: string;
  timestamp: Date;
  description: string;
}

export interface ContactForm {
  name: string;
  email: string;
  phone: string;
  message: string;
}