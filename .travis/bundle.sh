#!/usr/bin/env bash
################################################################################
#                                                                              #
# This script is used by Travis CI to create a downloadable build for each     #
# release with all dependencies, for manual installation.                      #
#                                                                              #
################################################################################

set -e
set -x
if [ "$TRAVIS_TAG" == "" ]; then
  exit 0
fi
cd $TRAVIS_BUILD_DIR
RELEASE_DIR=integernet-anonymizer-${TRAVIS_TAG}
mkdir -p ${RELEASE_DIR}
cp -rf src/* ${RELEASE_DIR}/
cp -rf .modman/psr0autoloader/app ${RELEASE_DIR}/
cp -rf .modman/psr0autoloader/shell ${RELEASE_DIR}/
cp -rf vendor/fzaninotto/faker/src/Faker ${RELEASE_DIR}/lib/
zip -r integernet-anonymizer.zip ${RELEASE_DIR}
tar -czf integernet-anonymizer.tar.gz ${RELEASE_DIR}
printf "\x1b[32mBundled release ${TRAVIS_TAG}\x1b[0m"