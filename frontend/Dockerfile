FROM node:12.16.3-alpine3.9

RUN mkdir -p /var/www/frontend
WORKDIR /var/www/frontend

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build

EXPOSE 3000

ENV NUXT_HOST=0.0.0.0

ENV NUXT_PORT=3000

CMD [ "npm", "start" ]