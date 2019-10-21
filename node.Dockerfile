FROM node:10
ENV NODE_ENV=development
ENV port=3000
COPY . /var/www
WORKDIR /var/www
RUN npm install
RUN npm run dev
CMD ["npm", "run", "watch"]
EXPOSE 3000