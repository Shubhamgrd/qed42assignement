**Setting Up a Local Development Environment for Drupal 10.x Using Docker**

## Introduction

This README provides detailed instructions for configuring a secure, efficient, and scalable local development environment for Drupal 10.x using Docker. The environment adheres to best practices in containerization for a seamless development experience.

## Prerequisites

**Docker**: Ensure Docker is installed on your system. Refer to the official Docker documentation for installation instructions: https://docs.docker.com/engine/install/

**Docker Compose**: Install Docker Compose Plugin https://docs.docker.com/compose/install/linux/

## Security Considerations

**Base Image**: Utilize an official, minimal base image like ubuntu:22.04.

**Permissions**: Grant minimal permissions to processes within the container.

**Exposure**: Only expose necessary ports (e.g., port 80 for Drupal).

**Environment Variables**: Store sensitive data like database credentials in a .env file outside version control (gitignore).

## Directory Structure

**Dockerfile.ubuntu**: This file contains instructions for building a Docker image based on the Ubuntu operating system. It's used to create a Docker image for running Drupal in a containerized environment.

**Dockerfile.ubuntu-multi**: Similar to Dockerfile.ubuntu, this file also contains instructions for building a Docker image based on Ubuntu. However, it's designed for multi-stage builds, aiming to create a smaller Docker image by separating the build and runtime environments.

**apache.conf**: This file contains the Apache configuration settings used by the Drupal containers to access the service. It specifies server configurations such as document root, logging, and directory permissions.

**docker-compose.yml**: This file is used to define and manage multi-container Docker applications. It describes the services, networks, and volumes required for the Dockerized Drupal environment, including services like MySQL, Drupal, and Nginx.

**nginx.conf**: Similar to apache.conf, this file contains the Nginx configuration settings used for Load Balancing of multiple Drupal containers. It specifies server configurations such as proxy settings, upstream servers, and HTTP handling.

**php.ini**: This file is a configuration file for PHP settings used by the Drupal containers. It configures PHP behavior according to the application's requirements, such as memory limit, timezone, and enabled extensions like GD.

**README.md**: This file! It provides instructions and information about setting up a local development environment for Drupal using Docker. It's the main documentation for the project.

## Building Images

**Single-Stage Build**

Navigate to the directory containing the Dockerfiles:

cd <project_directory>

docker build -f Dockerfile.ubuntu -t my_image_ubuntu .

Replace my_image_ubuntu with your desired image name.

**Multi-Stage Build**

Navigate to the directory containing the Dockerfiles:

cd <project_directory>

docker build -f Dockerfile.ubuntu-multi -t my_image_ubuntu_multi .

**Running the Application with Images**

Run the desired Drupal image:

docker run -d -p 80:80 <image_name>

Replace <image_name> with the name you used during the build process (e.g., my_image_ubuntu).

## Docker Compose

Navigate to the directory containing docker-compose.yml:

cd <project_directory>

Create an empty directory <drupal_data> for persistent storage:

mkdir drupal_data

Start the application with persistent storage and necessary services:

docker-compose up -d

This command builds the Drupal image (using the Dockerfile specified in docker-compose.yml) and starts the container with the associated database and other services.

## Accessing Drupal

Once the container is running, access your Drupal site by visiting:

http://localhost/<vm_public_ip>:80

You'll need to complete the Drupal installation process to set up your site.

**Using Environment Variables**

The project utilizes a .env file to manage sensitive environment variables like database credentials. This enhances security by keeping them out of the main docker-compose.yml file. Use the credentials mentioned in the .env file to configure your Drupal site.

**Manual Scaling**

To manually scale the application (e.g., for more concurrent users):

docker-compose up --scale <app_name>=3 -d

Replace <app_name> with the service name specified in docker-compose.yml (usually drupal) and adjust the number of instances (3 in this example) as needed.

## Further Explorations

**Refine Multi-Stage Docker Builds**: Investigate and optimize the multi-stage Dockerfile for potential size and performance benefits.

**Monitor Logs**: Consider integrating logging tools into docker-compose.yml to streamline log management and easier troubleshooting (e.g., centralized logging platforms).

**Version Control and Security**:
  Add .env to .gitignore to prevent sensitive information

## Reference Document:

https://vegastack.com/tutorials/how-to-install-drupal-on-ubuntu-22-04/

https://medium.com/devops-technical-notes-and-manuals/how-to-install-docker-on-ubuntu-22-04-b771fe57f3d2

https://medium.com/@tomer.klein/step-by-step-tutorial-installing-docker-and-docker-compose-on-ubuntu-a98a1b7aaed0

## Some Docker Command Used:
docker build -t <image_tag> .
docker run -d -p 8080:80 <image>
docker ps
docker volume ls
docker images
docker exec -it <container_id> /bin/bash
docker rmi <image>
docker volume rm <volume>
docker network ls
docker compose up -d
docker compose down
docker system prune
docker logs <container_id>

## Note
The php:8.1-apache base image was excluded due to intermittent issues. Surprisingly, the single-stage Ubuntu base image yielded the smallest image size for Drupal.

I've made several attempts to reduce the size of the Docker image, but haven't achieved significant success yet. Perhaps further exploration and experimentation in this area are needed.

In the healthcheck configuration, the 'curl' command functions properly with Dockerfile.ubuntu-multi, while 'wget' works with Dockerfile.ubuntu. Although we attempted to install 'curl' in Dockerfile.ubuntu, we encountered some issues, prompting us to modify the healthcheck configuration in the docker-compose.yml file.
