**Setting Up a Local Development Environment for Drupal 10.x Using Docker**
_This README provides detailed instructions for configuring a local development environment for Drupal 10.x using Docker. The environment aims to prioritize security, efficiency, and scalability, adhering to best practices in containerization._

**Prerequisites**
Ensure that Docker is installed on your system by following the instructions provided in the Docker documentation.

**Directory Structure**
The directory structure for this project includes the following components:
Dockerfiles:
Dockerfile.ubuntu: A single-stage Dockerfile based on the Ubuntu base image, used to build a Drupal image.
Dockerfile.ubuntu-multi: A multi-stage Dockerfile designed for building a smaller Drupal image by separating the build and runtime environments. However, it may have some issues that need to be addressed.
.env: This file is utilized for storing sensitive information as environment variables, enhancing security by avoiding the exposure of credentials in the Docker Compose files.
apache.conf: Configuration file for Apache used in the Drupal containers to access the service.
docker-compose.yml: This file enables the management of multiple containers using a single configuration file.
nginx.conf: Used for Load Balancing of multiple Drupal containers.
php.ini: Configuration file for PHP settings and enabling the GD extension, allowing customization of PHP behavior based on application requirements.

**Note**:
I attempted to use php:8.1-apache as the base image, but encountered intermittent issues. To eliminate ambiguity, I've removed it. Surprisingly, the smallest image size was achieved using the single-stage Ubuntu base image for Drupal.

**Security Considerations**
Base Image: Utilized an official, minimal base image such as ubuntu:22.04.
Permissions: Grant minimal permissions to processes within the container.
Exposure: Only expose necessary ports (e.g., port 80 for Drupal).
Environment Variables: Store sensitive data like database credentials in a .env file.
Building Images

**Single-Stage Build**:
cd <project_directory>/<directory_name>  # Replace with your directory path
docker build -f Dockerfile.ubuntu -t my_image_ubuntu .

**Multi-Stage Build**:
cd <project_directory>/<directory_name>  # Replace with your directory path
docker build -f Dockerfile.ubuntu-multi -t my_image_ubuntu_multi .

Running the Application with Images
docker run -d -p 80:80 <image_name>

**Docker Compose**
cd <project_directory>/<directory_name>  # Replace with your directory path
# Create an empty directory <drupal_data> for persistent storage, then
docker-compose up -d
This command builds the Drupal image (using the specified Dockerfile) and starts the container with the necessary services (e.g., database).

**Accessing Drupal**
Once the container is running, access your Drupal site by visiting http://localhost/<ec2_public_ip>:80 in your web browser. You'll need to complete the Drupal installation process to set up your site.

**Using Environment Variables**
This project utilizes a .env file to manage sensitive environment variables like database credentials. This improves security by keeping this information out of the main docker-compose.yml file. Use the credentials mentioned in the .env file to configure your Drupal site.

**Manual Scaling**
To manually scale the application, use the following command:
docker-compose up --scale <app_name>=3 -d
This command will scale the specified service to three instances. Adjust the <app_name> parameter as needed.

**More Improvements to Explore**:
I need to delve deeper into the utilization of multi-stage Docker builds, as the results of my previous attempt did not meet expectations.
Consider integrating monitoring tools into the Docker Compose files to streamline log management. This allows logs to be mapped to external tools for easier troubleshooting, rather than relying solely on manual inspection.
Remember not to share the .env file with other developers. Instead, manage it using the .gitignore file to safeguard sensitive information.
Consider setting up a domain for improved accessibility and organization. Additionally, explore implementing a development version of SSL certificates. Note that further exploration in this area was limited due to the usage of a public VM (AWS EC2).
