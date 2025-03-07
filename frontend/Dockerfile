# Use Node.js LTS version
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy project files
COPY . .

# Build Vue app
RUN npm run build

# Expose port for development (if needed)
EXPOSE 5173

# Start the app
CMD ["npm", "run", "dev"]
