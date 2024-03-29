# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.7.5 - 2016-02-02

### Added

- [#81](https://github.com/zendframework/zend-servicemanager/pull/81) adds a
  test covering forwards-compatibility features for plugin manager
  implementations.
- [#96](https://github.com/zendframework/zend-servicemanager/pull/96) adds
  `Zend\ServiceManager\Test\CommonPluginManagerTrait`, which allows you to test
  that your plugin manager is forwards compatible with v3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#91](https://github.com/zendframework/zend-servicemanager/pull/91) updates
  the `InvokableFactory` to add the `setCreationOptions()` method, allowing
  the `InvokableFactory` to accept `$options` when triggered.

## 2.7.4 - 2015-01-19

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#71](https://github.com/zendframework/zend-servicemanager/pull/71) fixes an edge case
  with alias usage, whereby an alias of an alias was not being resolved to the
  final service name.

## 2.7.3 - 2016-01-13

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#69](https://github.com/zendframework/zend-servicemanager/pull/69) fixes the
  way aliases are resolved to ensure that the original alias target, without
  canonicalization is passed to factories and abstract factories, ensuring that
  features such as the `InvokableFactory` implementation can work.

## 2.7.2 - 2016-01-11

### Added

- [#63](https://github.com/zendframework/zend-servicemanager/pull/63) adds a
  constructor to `InvokableFactory`. In v2, this allows plugin managers to pass
  construction options to the factory to use during instantiation of the
  requested service class, emulating the behavior of `build()` in v3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.7.1 - 2016-01-11

### Added

- [#61](https://github.com/zendframework/zend-servicemanager/pull/61) adds
  `Zend\ServiceManager\Exception\InvalidServiceException` for forwards
  compatibility with v3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#61](https://github.com/zendframework/zend-servicemanager/pull/61) updates
  the `InvokableFactory` to throw `InvalidServiceException` instead of
  `InvalidServiceNameException`, for forwards compatibility with v3.
- [#61](https://github.com/zendframework/zend-servicemanager/pull/61) fixes
  the behavior of `InvokableFactory` when invoked after resolving an alias.

## 2.7.0 - 2016-01-11

### Added

- [#60](https://github.com/zendframework/zend-servicemanager/pull/60) adds
  forward compatibility features for `AbstractPluingManager` and introduces
  `InvokableFactory` to help forward migration to version 3.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#46](https://github.com/zendframework/zend-servicemanager/pull/46) updates
  the exception hierarchy to inherit from the container-interop exceptions.
  This ensures that all exceptions thrown by the component follow the
  recommendations of that project.
- [#52](https://github.com/zendframework/zend-servicemanager/pull/52) fixes
  the exception message thrown by `ServiceManager::setFactory()` to remove
  references to abstract factories.

## 2.6.0 - 2015-07-23

### Added

- [#4](https://github.com/zendframework/zend-servicemanager/pull/4) updates the
    `ServiceManager` to [implement the container-interop interface](https://github.com/container-interop/container-interop),
    allowing interoperability with applications that consume that interface.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#3](https://github.com/zendframework/zend-servicemanager/pull/3) properly updates the
  codebase to PHP 5.5, by taking advantage of the default closure binding
  (`$this` in a closure is the invoking object when created within a method). It
  also removes several `@requires PHP 5.4.0` annotations.
