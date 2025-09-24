# Nginx Dockerfile
FROM nginx:alpine

# คัดลอก config ของ nginx
COPY docker/nginx/conf.d /etc/nginx/conf.d

# คัดลอกไฟล์ source ไปที่ /var/www/html
COPY src /var/www/html

# กำหนดการใช้ Nginx
CMD ["nginx", "-g", "daemon off;"]
