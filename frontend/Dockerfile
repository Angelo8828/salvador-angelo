# Use Node.js LTS version
FROM node:18

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy project files
COPY . .

# Change ownership of the working directory to a non-root user
RUN chown -R node:node /app

# Switch to non-root user
USER node

# Build Vue app
RUN npm run build

# Expose port for development (if needed)
EXPOSE 5173

# Start the app
CMD ["npm", "run", "dev"]
