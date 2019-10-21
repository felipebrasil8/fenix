FROM node:10
ENV NODE_ENV=development
ENV port=3000
COPY . /var/www
WORKDIR /var/www
RUN npm install --global cross-env 
CMD ["npm", "run", "dev"]
EXPOSE 3000
